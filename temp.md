## Índice
```
${query[[
  from p = index.tag("page")
  where p.name != "index" and string.find(p.name, "/")
  order by p.name asc
  select string.rep("  ", #string.gsub(p.name, "[^/]", "") - 1) .. "* **" .. (string.match(p.name, "(.+)/") or "") .. "/** ➔ [[" .. p.name .. "|" .. (string.match(p.name, "[^/]+$") or p.name) .. "]]"
]]}
```

```
${query[[
  from p = index.tag("page")
  where p.name != "index"
  order by p.name asc
  select string.rep("  ", #string.gsub(p.name, "[^/]", "")) .. "* [[" .. p.name .. "]]"
]]}
```

