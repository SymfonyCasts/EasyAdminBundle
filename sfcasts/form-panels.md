# Form Panels

Last topic! We made it! And our admin is getting *super* customized. For this final
trick, I want to look closer at the form. Almost all of this is controlled by the
`Field` configuration. Each field corresponds to a Symfony form type... and then
EasyAdmin renders those fields through the form system. It really *is* that simple.

## Custom Form Theme

EasyAdmin comes with a custom form theme. So if you wanted to, for example, make
a text type field look different in EasyAdmin, you could create a *custom* form
theme template. This theme can be added to the `$crud` object in `configureCrud()`.
Down here, for example, we could say `->addFormTheme()` to add our form theme
template to just *one* CRUD controller... *or* you could put this in the dashboard
to apply everywhere.

## Form Panel

*But*, apart from a custom form theme, there *are* a few *other* ways that EasyAdmin
allows us to control what this page looks like... which, right now, is just a long
list of fields.

Over in `QuestionCrudController`, up in `configureFields()`... here we go... right
before the `askedBy` field, add `yield FormField::`. So we're starting like normal,
but instead of saying `new`, say `addPanel('Details')`.

Watch what this does! Refresh and... cool! "Asked By" and "Answers" appear under
this "Details" header. That's because, as you can see, `askedBy` and `answers`
are the two fields that appear *after* the `addPanel()` call. And because the rest
of these fields are *not* under a panel, they just... kind of appear at the bottom,
which *works*, but doesn't look the greatest.

So, when I use `addPanel()`, I put *everything* under a panel. Right after
`IdField`, which *isn't* going to appear on the form, say
`FormField::addPanel('Basic Data')`. Oh! And let me make sure I don't forget to
`yield` that.

Thanks to this... awesome! We have a "Basic Data" panel, all of the fields below
that, then the second panel down here.

## Customizing the Panels

These panels have a few methods on them. One of the most useful is `->collapsible()`.
Make this panel collapsible... and the other as well.

I bet you can guess what this does. Yep! We get a nice way to collapse each section.

What else can we tweak? How about `->setIcon('fa fa-info')`... or
`->setHelp('Additional Details)`?

Oh, I actually meant to put this down on the other panel, so let me grab this...
find that other panel... here we go... and paste.

Let's check it out! Nice! The second panel has an icon and some sub-text.

By the way, the changes we're making not only affect the form page, but also the
Detail page. Go check out the Detail page for one of these. Yup! The same
organization is happening here, which is nice.

## Form Tabs

If you want to organize things even a bit *more*, instead of panels, you can use
tabs. Change `addPanel()` to `addTab()`. And... repeat that below: `addTab()`.

When we refresh now... yup! Each shows up as a separate tab. But the `->collapsible()`
doesn't really make sense anymore. It *is* still being called, but it doesn't
do anything. So, remove that.

## Fixing the Icon on the Tab

Oh, and we also lost our icon! We added an `fa fa-info` icon... but it's not showing!
Or *is* it? If you look closely, there's some extra space. Inspect element on that.
There *is* an icon! But... it looks... weird. It has an extra `fa-fa` for some reason.

We can fix this by changing the icon to, simply, `info`. This is... sort of a bug.
Or, it's at least inconsistent. When we use tabs, EasyAdmin adds the `fa-` for us.
So all we need is `info`. Watch: when I refresh... there! `fa-info`... and *now*
the icon shows up!

## Form Columns

The *last* thing we can do, instead of having this long list of fields, is to put
the fields *next* to each other. We do this by controlling the *columns* on this
page.

To show this off, move the `name` field above `slug`. Yup, got it. And now let's
see if we can put these fields *next* to each other. We're using bootstrap,
which means there are 12 invisible columns on each page. So, on `name`,
say `->setColumns(5)`... and on `slug`, do the same thing: `->setColumns(5)`.

We could use `6` to take up *all* of the space, but I'll stick with `5` and give
it some room. Refresh now and... very nice! The fields float next to each other.
This is a great way to help this page... make a bit more sense.

And... that's it, friends! We are *done*! This was fun! We should do it again sometime.
I *love* EasyAdmin, and we here at SymfonyCasts are *super* proud of the admin
section we built with it... which includes *a lot* of custom stuff. Let us know what
you're building! And as always, we're here for you down in the Comments section with
any questions, ideas, or delicious recipes that you might have.

All right friends, see you next time!
