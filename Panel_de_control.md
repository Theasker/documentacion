## Consulta 1

### Últimas páginas modificadas:
`${query[[from tags.page order by lastModified desc limit 5]]}`


${query[[
  from tags.page 
  order by lastModified desc limit 10
  select {name, lastModified}
]]}


```lua
${query[[
from index.tag "task"
select {page, links}
]]}
```
${query[[
from index.tag "task"
select {page, links}
]]}
