# Conectar un dominio propio a una ip dinámica

Your API Key
8d682c98dd3babb414bf164c48d3e85c76d42


Zone ID
d07f4eac5e174fb73e68100ecf8c31b0


> https://360techexplorer.com/cloudflare-dynamic-dns-raspberry-pi/


If you want to do anything with your home network or raspberry pi from outside of your [network](https://360techexplorer.com/computer-networking/) or from the Internet, you must have i static IP which costs you money, another option is Cloudflare dynamic DNS Raspberry Pi.

Here we set up our DDNS with Cloudflare on Raspberry Pi.  
If you do not have money to [buy a domain](https://360techexplorer.com/should-i-buy-domain-through-shopify/) this tutorial is still helpful for you, stay here!

Pre-requisites for Setup DDNS on Cloudflare
-------------------------------------------

1.  The domain name (Do not worry if you do not have one, I will take care of it!)

**A quick note: the .tk, .cl, etc domain will not work but do not worry scroll down I will show you how you get a FREE domain.**

2.  Linux machines or Raspberry Pi
3.  A script (that I am going to give you)
4.  Cloudflare Account

Ok, everything you need to know now let’s jump into the main part of this tutorial.

#### **For those, who do not have a Domain**

1.  Go to [Github Education pack](https://education.github.com/pack/offers)
2.  And sign up for this
3.  After getting approved choose any domain you want

**Type offer**: .tech, .me, etc domain for 100% FREE

Step 1: Connect your domain to Cloudflare
-----------------------------------------

1.  (If you do have a Cloudflare account) visit [https://dash.cloudflare.com/sign-up](https://dash.cloudflare.com/sign-up).
2.  Login to your Cloudflare account
3.  Click on Add site
4.  Enter your website’s root domain and then click Add Site.

_E.g: If your website is www.example.com, type example.com._

5.  Click Next.
6.  Select a plan level, I go with a Free plan
7.  Click Confirm in the Confirm Plan window that appears.
8.  Copy the 2 Cloudflare nameservers displayed.  
9.  Setup those nameservers for your  domain

_In this step, you can also knock your Domain registrar for help_

10.  Wait for nameserver to update

This process can take up to 24 hours but in my personal experience, it could take only a few minutes.

Step 2: Create A record for Raspberry Pi
----------------------------------------

1.  Login to Cloudflare 
2.  Go to Cloudflare Dashboard Home
3.  Choose your domain 
4.  Navigate to its DNS tab
5.  Select Add record.
6.  Choose “A” as type
7.  Enter your desired subdomain name like “home-network” as Name
8.  As the IPv4 address, enter 0.0.0.0
9.  Finally, click on save.

Step 3: Grab the necessary details from Cloudflare
--------------------------------------------------

Here you need 2 things,  
Frist is Gobel API Token

1.  Login to Cloudflare 
2.  Go to Cloudflare Dashboard Home
3.  Choose your domain 
4.  Scroll down the page till you see an API section on the right sidebar
5.  Under the API section click on “Get your API token”
6.  Here from top navigation tabs click on “API Tokens”
7.  Scroll down until you see “Globel API Key”
8.  Click on View
9.  Copy the API here!
10.  Take this into a safe place

The second one is zone Id

1.  Login to Cloudflare 
2.  Go to Cloudflare Dashboard Home
3.  Choose your domain 
4.  Scroll down the page till you see an API section on the right sidebar
5.  Under the API section copy the Zone ID
6.  Take this into a safe place

Step 4: Setup File for DDNS on Raspberry Pi
-------------------------------------------

1.  Open terminal or SSH on Raspberry Pi
2.  Make a folder by executing ‘mkdir cloudflare-ddns-updater’

    $ sudo mkdir cloudflare-ddns-updater

3.  Make a file by executing ‘touch cloudflare.sh’

    $ sudo touch cloudflare.sh

4.  Copy the below script and past it into cloudflare.sh (created above)
```bash
#!/bin/bash

auth_email=""                                      # The email used to login 'https://dash.cloudflare.com'
auth_key=""                                        # Top right corner, "My profile" > "Global API Key"
zone_identifier=""                                 # Can be found in the "Overview" tab of your domain
record_name=""                                     # Which record you want to be synced
proxy=true                                         # Set the proxy to true or false 

###########################################
## Check if we have an public IP
###########################################
ip=$(curl -s https://api.ipify.org || curl -s https://ipv4.icanhazip.com/)
if [ "${ip}" == "" ]; then 
    message="No public IP found."
    >&2 echo -e "${message}" >> ~/log
    exit 1
fi

###########################################
## Seek for the A record
###########################################
echo " Check Initiated" >> ~/log
record=$(curl -s -X GET "https://api.cloudflare.com/client/v4/zones/$zone_identifier/dns_records?name=$record_name" -H "X-Auth-Email: $auth_email" -H "X-Auth-Key: $auth_key" -H "Content-Type: application/json")

###########################################
## Check if the domaine has an A record
###########################################
if [[ $record == *""count":0"* ]]; then
    message=" Record does not exist, perhaps create one first? (${ip} for ${record_name})"
    >&2 echo -e "${message}" >> ~/log
    exit 1
fi

###########################################
## Get the existing IP 
###########################################
old_ip=$(echo "$record" | grep -Po '(?<="content":")[^"]*' | head -1)
# Compare if they're the same
if [[ $ip == $old_ip ]]; then
    message=" IP ($ip) for ${record_name} has not changed."
    echo "${message}" >> ~/log
    exit 0
fi

###########################################
## Set the record identifier from result
###########################################
record_identifier=$(echo "$record" | grep -Po '(?<="id":")[^"]*' | head -1)

###########################################
## Change the IP@Cloudflare using the API
###########################################
update=$(curl -s -X PUT "https://api.cloudflare.com/client/v4/zones/$zone_identifier/dns_records/$record_identifier" 
                        -H "X-Auth-Email: $auth_email" 
                        -H "X-Auth-Key: $auth_key" 
                        -H "Content-Type: application/json" 
                --data "{"id":"$zone_identifier","type":"A","proxied":${proxy},"name":"$record_name","content":"$ip"}")

###########################################
## Report the status
###########################################
case "$update" in
*""success":false"*)
    message="$ip $record_name DDNS failed for $record_identifier ($ip). DUMPING RESULTS:n$update"
    >&2 echo -e "${message}" >> ~/log
    exit 1;;
*)
    message="$ip $record_name DDNS updated."
    echo "${message}" >> ~/log
    exit 0;;
esac
```

5.  Open the file in your favorite editor (I use nano for this)

    $ nano cloudflare.sh

6.  Config the script,
7.  Enter the email that you used to sign up on Cloudflare as auth\_email
8.  Enter the Glodel API key that you copied on step 3 as auth\_key
9.  Enter the Zone ID that you copied on step 3 as zone\_identifier
10.  Enter the subdomain (with the root domain, e.g home-network.mydomain.com) you choose in step 2 as record\_name
11.  Close the file by pressing Ctrl + X then press ‘Y’ to save.
12.  Make the script executable by executing the following command

    $ sudo chmod +x cloudflare.sh

_Now everything is done if you want to test, run the script, and see the Cloudflare dashboard record._

Step 5: Automate the script
---------------------------

1.  Open crontab in edit mode

    $ sudo cronttab -e

2.  Choose your editor I go with ‘nano’ by pressing 1
3.  Add the following line to this file

    */1 * * * * /bin/bash [PATH_TO_SCRIPT]
    
    E.g:
    */1 * * * * /bin/bash /root/cloudflare-ddns-updater/cloudflare.sh

4.  Close the file by pressing Ctrl + X then press ‘Y’ to save.

That’s all you need.  
After a minute you see your DNS will be replaced with your Public IP of course automatically.  
If you have any questions or issues regarding this feel free to commend here.