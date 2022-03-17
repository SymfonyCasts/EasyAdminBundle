# Having Fun with the Menu

Our menu on the left is getting a *little* long and... *kind of* confusing... since
we now have *two* question links. To make this more user-friendly, let's divide
this into a sub-menu. We do that inside of `DashboardController`... because that's,
of course, where we configure the menu items.

## Adding a Sub Menu

To create a sub-menu, say `yield MenuItem::subMenu()` and then give that a name -
like `Questions` - and an icon... just like we do with normal menu items.

To populate the *items* in this menu, say `->setSubItems()`, pass this an array,
and then we'll wrap our other two menu item objects *inside* of this. Of course,
now we need to indent, remove the `yield`, and... replace the semicolons with
commas.

Perfect! Now change `Questions` to... how about `All`... and let's play with
the icons. Change the first to `fa fa-list`... and the second to `fa fa-warning`.

Let's try that. Move over... refresh and... ahhh, much cleaner!

## Menu Sections

But wait, there's *more* we can do with the menu... like adding separators...
technically called "sections". Right after `linkToDashboard()`, add
`yield MenuItem::section()` and pass it `Content`.

Let's put one more down here - `yield MenuItem::section()`... but this time leave the
label blank. So unlike sub-menus, which *wrap* menu items, you can just pop
a section anywhere that you want a separator.

Let's go check it out. Refresh and... very nice! Separator one says "Content"...
and separator two gives us a little gap without any text.

## External Links

We saw earlier that you can add menu links to point to a dashboard, other CRUD
sections... or just *any* URL you want, like the Homepage. So, not surprisingly, you
can *also* link to external sites. For instance, let's say that I love StackOverflow
*so* much, that I want to link to it. We can tweak the icons, and for the URL,
pass whatever you want, like https://stackoverflow.com.

Oh, but let me fix my icon name. Great! Now when we refresh... no surprise, that
works fine.

## More Menu Item Options

If you look closer at these menu items, you'll see that they have a *lot* of options
on them! We know we have things like `setPermission()` and `setController()`, but
we *also* have methods like `setLinkTarget()`, `setLinkRel()`, `setCssClass()`,
or `setQueryParameter()`. For this case, let's `->setLinkTarget('_blank')`... so
that *now* if I click "StackOverflow", it pops up in a new tab.

Next: what if we need to disable an action on an entity-by-entity basis? Like,
we want only want to allow questions to be deleted if they are *not* approved.
Let's dive into that.
