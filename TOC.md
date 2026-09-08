# Índice general

#TOC

${query[[
  from p = index.tag("page")
  where p.name != "index"
    and not string.match(p.name, "^Library/")
    and not string.match(p.name, "^Space/")
    and not string.match(p.name, "^`Repositories`/")
  order by p.name asc
  select string.rep("  ", #string.gsub(p.name, "[^/]", "")) .. "* `/" .. (string.match(p.name, "(.+)/") or "raíz") .. "/` ➔ [[" .. p.name .. "|" .. (string.match(p.name, "[^/]+$") or p.name) .. "]]"
]]}
