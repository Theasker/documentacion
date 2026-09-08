# índice de tareas


${some(query[[
  from t = index.tasks()
  where not t.done
  order by t.pageLastModified
  desc limit 10
  select templates.taskItem(t)
]]) or "_All tasks done!_"}


${template.each(query
[[ 
  from index.tag "task" 
  order by _.date desc 
]], 
templates.taskItem )}
