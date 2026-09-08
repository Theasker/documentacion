# SASS en Codeacademy
<!-- TOC -->

- [SASS en Codeacademy](#sass-en-codeacademy)
    - [Nesting](#nesting)
        - [Selectors](#selectors)
        - [Properties](#properties)
    - [Variables In Sass](#variables-in-sass)
    - [SASS Types](#sass-types)
        - [Maps & Lists](#maps--lists)
    - [The "&" Selector in Nesting](#the--selector-in-nesting)
    - [Mixin?](#mixin)
        - [Mixins facts](#mixins-facts)
        - [List Arguments](#list-arguments)

<!-- /TOC -->
## Nesting

### Selectors

`scss`
```css
.banner {
  font-family: 'Pacifico', cursive;
  height: 400px;
  background-image: url("lemonade.jpg");
  .slogan {
    position: absolute;
    border: 4px solid black;
    top: 200px;
    left: 25%;
    width: 50%;
    height: 200px;
    text-align: center;
    span {
      font-size: 24px;
      line-height: 200px;
    }
  }
}
```

`css`
```css
.banner {
  font-family: 'Pacifico', cursive;
  height: 400px;
  background-image: url("lemonade.jpg");
}

.banner .slogan {
  position: absolute;
  border: 4px solid black;
  top: 200px;
  left: 25%;
  width: 50%;
  height: 200px;
  text-align: center;
}

.banner .slogan span {
  font-size: 24px;
  line-height: 200px;
}
```

### Properties

`scss`

```css
.parent {
  font : {
    family: Roboto, sans-serif;
    size: 12px;
    decoration: none;
  }
}
```
`css`
```css
.parent {
  font-family: Roboto, sans-serif;
  font-size: 12px;
  font-decoration: none;
}
```
## Variables In Sass

`$translucent-white: rgba(255,255,255,0.3);`

## SASS Types

1. Numbers, such as 8.11, 12, and 10px. Notice that while 10 has a unit of px associated with it, it is still considered a number.
2. Strings of text, with and without quotes. Some examples are "potato", 'tomato', span.
3. Booleans, or simply true and false.
4. null, which is considered an empty value.

### Maps & Lists

* **Lists** can be separated by either spaces or commas.

`1.5em Helvetica bold;`

or
 
`Helvetica, Arial, sans-serif;`

**Note:** You can also surround a list with parentheses and create lists made up of lists.

* **Maps** are very similar to lists, but instead each object is a key-value pair. The typical map looks like:

`(key1: value1, key2: value2);`

**Note:** In a map, the value of a key can be a list or another map.

## The "&" Selector in Nesting

In Sass, the `&` character is used to specify exactly where a parent selector should be inserted. It also helps write psuedo classes in a much less repetitive way.

`scss`
```css
.notecard{ 
  &:hover{
      @include transform (rotatey(-180deg));  
    }
  }
```

`css`
```css
.notecard:hover {
  transform: rotatey(-180deg);
}
```

## Mixin?

In Sass, a mixin lets you make groups of CSS declarations that you want to reuse throughout your site. It's like a function.

`scss`
```css
@mixin backface-visibility($visibility) {
  backface-visibility: $visibility;
  -webkit-backface-visibility: $visibility;
  -moz-backface-visibility: $visibility;
  -ms-backface-visibility: $visibility;
  -o-backface-visibility: $visibility;
}
```
```css
.notecard {
.front, .back {
    width: 100%;
    height: 100%;
    position: absolute;

    @include backface_visibility(hidden);
  }
}
```
is equivalent to the following CSS:

```css
.notecard .front, .notecard .back {
  width: 100%;
  height: 100%;
  position: absolute;

   backface-visibility: hidden;
  -webkit-backface-visibility: hidden; 
  -moz-backface-visibility: hidden;
  -ms-backface-visibility: hidden;
  -o-backface-visibility: hidden;
}
```

**Default Value Arguments**

`@mixin backface-visibility($visibility: hidden) { ...`

### Mixins facts

1. Mixins can take multiple arguments.
2. Sass allows you to explicitly define each argument in your @include statement.
3. When values are explicitly specified you can send them out of order.
4. If a mixin definition has a combination of arguments with and without a default value, you should define the ones with no default value first.
5. Mixins can be nested.

```css
@mixin dashed-border($width, $color: #FFF) {
  border: {
     color: $color;
     width: $width;
     style: dashed;
  }
}

span { //only passes non-default argument
    @include dashed-border(3px);
}

p { //passes both arguments
    @include dashed-border(3px, green);
}

div { //passes out of order but explicitly defined
   @include dashed-border(color: purple, width: 5px); 
}
```

### List Arguments

Sass allows you to pass in multiple arguments in a list or a map format.

```css
@mixin stripes($direction, $width-percent, $stripe-color, $stripe-background: #FFF) {
  background: repeating-linear-gradient(
    $direction,
    $stripe-background,
    $stripe-background ($width-percent - 1),
    $stripe-color 1%,
    $stripe-background $width-percent
  );
}
```

In this scenario, it makes sense to create a map with these properties in case we ever want to update or reference them.

```css
$college-ruled-style: ( 
    direction: to bottom,
    width-percent: 15%,
    stripe-color: blue,
    stripe-background: white
);
```

When we include our mixin, we can then pass in these arguments in a map with the following ... notation:

```css
.definition {
      width: 100%;
      height: 100%;
      @include stripes($college-ruled-style...);
 }
 ```
 
### String Interpolation

In Sass, **string interpolation** is the process of placing a variable string in the middle of two other strings.

```css
mixin photo-content($file) {
  content: url(#{$file}.jpg); //string interpolation
  object-fit: cover;
}


//....

.photo { 
  @include photo-content('titanosaur');
  width: 60%;
  margin: 0px auto; 
}
```

String interpolation would enable the following CSS:

```css
.photo { 
  content: url(titanosaur.jpg);
  width: 60%;
  margin: 0px auto;
}
```

### The "&" Selector in Mixins

1. The & selector gets assigned the value of the parent at the point where the mixin is included.
2. If there is no parent selector, then the value is null and Sass will throw an error.

```css
@mixin text-hover($color){
  &:hover {
      color: $color; 
  }
}
```

In the above, the value of the parent selector will be assigned based on the parent at the point where it is invoked.
```css
  .word { //SCSS: 
    display: block; 
    text-align: center;
    position: relative;
    top: 40%;
    @include text-hover(red);
  }
```

The above will compile to the following CSS:
```css
  .word{ 
    display: block;
    text-align: center;
    position: relative;
    top: 40%;
  }
  .word:hover{
    color: red;
  }
```

## Functions and Operations

### Functions in SCSS

* Operate on color values
* Iterate on lists and maps
* Apply styles based on conditions
* Assign values that result from math operations

### Arithmetic and Color

The alpha parameter in a color like RGBA or HSLA is a mask denoting opacity. It specifies how one color should be merged with another when the two are on top of each other.

* In Sass, the function fade-out makes a color more transparent by taking a number between 0 and 1 and decreasing opacity, or the alpha channel, by that amount:
```css
   $color: rgba(39, 39, 39, 0.5);
   $amount: 0.1;
   $color2: fade-out($color, $amount);//rgba(39, 39, 39, 0.4)
```

* The fade-in color function changes a color by increasing its opacity:
```css
$color: rgba(55,7,56, 0.5);
$amount: 0.1;
$color2: fade-in($color, $amount); //rgba(55,7,56, 0.6)
```

* The function adjust-hue($color, $degrees) changes the hue of a color by taking color and a number of degrees (usually between -360 degrees and 360 degrees), and rotate the color wheel by that amount.

### Color Functions

Here is how Sass computes colors:

1. The operation is performed on the red, green, and blue components.
2. It computes the answer by operating on every two digits.

`$color: #010203 + #040506;`

The above would compute piece-wise as follows:
```css
01 + 04 = 05
02 + 05 = 07
03 + 06 = 09
```
and compile to:

`color: #050709;`

Sass arithmetic can also compute HSLA and string colors such as red and blue.

### Arithmetic

The Sass arithmetic operations are:

* addition `+`
* subtraction `-`
* multiplication `*`
* division `/`, and
* modulo `%`.

SCSS arithmetic requires that the units are compatible; for example, you can't multiply pixels by ems.

Also, just like in regular math, multiplying two units together results in squared units:10px * 10px = 100px * px.

Since there is no such thing as squared units in CSS, the above would throw an error. You would need to multiply 10px * 10 in order to obtain 100px

#### Division Can Be Special

In CSS the / character can be used as a separator. In Sass, the character is also used in division.

Here are the specific instances / counts as division:

1. If the value, or any part of it, is stored in a variable or returned by a function.
2. If the value is surrounded by parentheses, unless those parentheses are outside a list and the value is inside.
3. If the value is used as part of another arithmetic expression.

Here are a few examples:
```css
width: $variable/6; //division
line-height: (600px)/9; //division
margin-left: 20-10 px/ 2; //division
font-size: 10px/8px; //not division
```
`scss`
```css
$width: 250px;
$lagoon-blue: #62fdca;
$lagoon-blue: fade-out(#62fdca, 0.5);
$color: red + blue;
$border: $width/30;

.math {
  width: $width;
  text-align: center;
  background-color: $lagoon-blue;
  color: $color;
  height: $width/6;
	line-height: $width/6;
  border-radius: $border;
  font-size: $width/6/2;
}
```
`css`
```css
.math {
  width: 250px;
  text-align: center;
  background-color: rgba(98, 253, 202, 0.5);
  color: magenta;
  height: 41.66667px;
  line-height: 41.66667px;
  border-radius: 8.33333px;
  font-size: 20.83333px;
}
```

### Each Loops

Each loops in Sass iterate on each of the values on a list. The syntax is as follows:
```css
@each $item in $list {
  //some rules and or conditions
}
```
### For Loops

```css
@for $i from $begin through $end {
   //some rules and or conditions
}
```

Example:
`scss`
```css
$total: 10; //Number of .ray divs in our html
$step: 360deg / $total; //Used to compute the hue based on color-wheel


.ray {
  height: 30px;
}

@for $i from 1 through $total {
  .ray:nth-child(#{$i}) {
    background: adjust-hue(blue, $i * $step);
   }
  
}
```



### Conditionals

`width: if( $condition, $value-if-true, $value-if-false);`

```css
@mixin deck($suit) {
 @if($suit == hearts || $suit == spades){
   color: blue;
 }
 @else-if($suit == clovers || $suit == diamonds){
   color: red;
 }
 @else{
   //some rule
 }
}
```

## @Import in SCSS

Sass extends the existing CSS @import rule to allow including other SCSS and Sass files.

### Organize with Partials

Partials in Sass are the files you split up to organize specific functionality in the codebase.

They use a _ prefix notation in the file name that tells Sass to hold off on compiling the file individually and instead import it.

`_filename.scss`

To import this partial into the main file - or the file that encapsulates the important rules and the bulk of the project styles - omit the underscore.
For example, to import a file named _variables.scss, add the following line of code:

`@import "variables";`

### @Extend

Many times, when styling elements, we want the styles of one class to be applied to another in addition to its own individual styles, so the traditional approach is to give the element both classes.

```css
<span class="lemonade"></span>

<span class="lemonade strawberry"></span>
```

This is a potential bug in maintainability because then both classes always have to be included in the HTML in order for the styles to be applied.

Enter Sass's @extend. All we have to do is make our strawberry class extend .lemonade and we will no longer have this dilemma.

```css
.lemonade {
  border: 1px yellow;
  background-color: #fdd;
}
.strawberry {
  @extend .lemonade;
  border-color: pink;
}
```

If you observe CSS output, you can see how @extend is working to apply the .lemonade rules to .strawberry:

```css
.lemonade, .strawberry {
  border: 1px yellow;
  background-color: #fdd;
}

.strawberry {
  @extend .lemonade;
  border-color: pink;
}
```

If we think of .lemonade as the extendee, and of .strawberry as the extender, we can then think of Sass appending the extender selector to the rule declarations in the extendee definition.

This makes it easy to maintain HTML code by removing the need to have multiple classes on an element.

### %Placeholders

Sometimes, you may create classes solely for the purpose of extending them and never actually use them inside your HTML.

Sass anticipated this and allows for a special type of selector called a placeholder, which behaves just like a class or id selector, but use the % notation instead of # or .

Placeholders prevent rules from being rendered to CSS on their own and only become active once they are extended anywhere an id or class could be extended.

```css
 a%drink {
    font-size: 2em;
    background-color: $lemon-yellow;
 }

 .lemonade {
  @extend %drink;
  //more rules
 }
```

would translate to

```css
  a.lemonade {
    font-size: 2em;
    background-color: $lemon-yellow;
 }

.lemonade {
  //more rules
}
```

Placeholders are a nice way to consolidate rules that never actually get used on their own in the HTML.

### @Extend vs @Mixin

Sweet! Recall that mixins, unlike extended selectors, insert the code inside the selector's rules wherever they are included, only including "original" code if they are assigning a new value to the rule's properties via an argument.

Let's look at the @mixin and @extend constructs closely and compare output:

```css
@mixin no-variable {
  font-size: 12px;
  color: #FFF;
  opacity: .9;
}

%placeholder {
  font-size: 12px;
  color: #FFF;
  opacity: .9;
}

span {
  @extend %placeholder;
}

div {
  @extend %placeholder;
}

p {
  @include no-variable;
}

h1 {
  @include no-variable;
}
```

would compile to:
```css
span, div{
  font-size: 12px;
  color: #FFF;
  opacity: .9;
}

p {
  font-size: 12px;
  color: #FFF;
  opacity: .9;
  //rules specific to ps
}

h1 {
  font-size: 12px;
  color: #FFF;
  opacity: .9;
  //rules specific to ps
}
```

We can clearly see extending results in way cleaner and more efficient output with as little repetition as possible.

As a general rule of thumb, you should

Try to only create mixins that take in an argument, otherwise you should extend.
Always look at your CSS output to make sure your extend is behaving as you intended.