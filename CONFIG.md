# Archivo de configuración

#Config

## Treeview


> [Githube de Treeview](https://github.com/joekrill/silverbullet-treeview)

```space-lua
config.set {
  plugs = {
    "github:joekrill/silverbullet-treeview/treeview.plug.js"
  },
  -- The treeview plug configuration
  treeview = {
    -- Determines where the panel is displayed:
    -- - "lhs" - left hand side
    -- - "rhs" - right hand side
    -- - "bhs" - bottom
    -- - "modal" - in a modal
    position = "lhs",

    -- Must be > 0.
    -- position = "lhs" | "rhs": determines the width of the panel.
    -- position = "modal": sets the margin around the modal window.
    -- position = "bhs": No effect
    size=0.5,

    dragAndDrop = {
      -- Set to false to disable drag-and-drop
      enabled = true,

      -- Set to false to disable the confirmation prompt shown when dragging and
      -- dropping pages that causes them to be renamed/moved.
      confirmOnRename = true
    },

    -- An array of exclusion rules that will exclude pages from being
    -- displayed in the sidebar.
    exclusions = {
      {
        -- Filter by regular expression:
        type = "regex",
        -- Regular Expression string to exclude pages from the tree
        -- Examples:
        -- - Any page that is all-caps: "^[A-Z]+$"
        -- - A specific set of pages: "^(?:CONFIG|Library|index).*$"
        -- - Any path containing Hidden (e.g. test/Hidden/page1): "Hidden"
        -- rule="^(?:CONFIG|Library|index).*$",
        rule = "^(?:CONFIG|Library|Repositories).*$",
        -- Optional: set to true to negate the rule, only showing pages that match this regex.
        negate= false,
      },
      {
        -- Filter by page tags:
        type = "tags",
        tags = {"meta"},
        -- Optional: set to true to negate the rule, only showing pages that include any of the tags.
        negate = false
      }
    }
  }
}
```
### Botón superior de treeview

```space-lua
actionButton.define {
  icon = "sidebar",
  description = "Toggle Tree View",
  run = function()
    editor.invokeCommand("Navigate: Tree")
  end
}
```

