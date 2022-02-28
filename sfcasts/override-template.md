# Override all the Templates!

Everything you see in EasyAdmin, from the layout to this table, to even how the
individual fields are rendered, is controlled by a template. EasyAdmin has a
*bunch* of templates and we can override *all* of them.

We looked at these a bit earlier. Let's dive back into
`vendor/easycorp/easyadmin-bundle/src/Resources/views/`. There's a *lot* of good
stuff here, like `layout.html.twig`. This is the base layout file for *every* page.
A few minutes ago, we also saw `content.html.twig`. This is a nice layout template
that you can extend if you're creating a custom page inside of EasyAdmin.

Inside of `crud/`, we see the templates for the individual pages and in
`field/`, there's a template that controls how *every* field type is rendered.

Inside a lot of these templates, you'll see things like
`ea.templatePath('layout')`. To understand that more deeply, hit "shift+shift" and
open up a core class that we opened earlier called `TemplateRegistry`.

Internally, EasyAdmin maintains a map from a template "name" to an actual template
path. So when you see something like `ea.templatePath('layout')`, that's going to
use `TemplateRegistry` to figure out to load `@EasyAdmin/layout`, where `@EasyAdmin`
is a Twig alias that points to this `views/` directory.

## Overriding a Core Template

With that in mind, here's our goal: I want to add a footer to the bottom
of *every* page. Look again at `layout.html.twig`. Near the bottom, let's see...
search for "footer". There we go! This has a block called `content_footer`. So
if you *define* a `content_footer`, then it will get dumped right here. Let's override
the `layout.html.twig` template and add a new `content_footer`.

There are two ways to do this. The first is to use Symfony's normal system for
overriding templates that live inside of a bundle. You do that by creating a file
in a very specific path. Inside of `templates/`, create a directory called
`bundles/`... and inside of that, another directory with the name of the bundle:
`EasyAdminBundle`. Now, match whatever path from the bundle that you want to override.
Since we want to override `layout.html.twig`, create a new file called
`layout.html.twig`.

But, hmm. I don't really want to override this *entirely*. I want to *extend*
it. And, we can do that! Add `{% extends %}`, with `@EasyAdmin/layout.html.twig`.
The only problem is that, by creating a `templates/bundles/EasyAdmin/layout.html.twig`
template, we have now *overridden* the `@EasyAdmin/layout.html.twig`. In other words,
we're effectively extending *ourselves*!
`layout.html.twig`

To tell Symfony that we want to use the *original* template, we can put an exclamation
point in front.

Perfect! Below, add `{% block content_footer %}` and `{% endblock %}`... with a
nice little message inside.

Let's try it! Refresh *any* page and... hello, footer! Sweet!

## Overriding a Field Template

I mentioned earlier that there are *two* ways to override templates in EasyAdmin.
The second one is even *more* powerful because it allows us to control exactly *when*
we want our override templates to be used. Let me close a couple of files.

For our next trick, I want to override the template that's used to render id fields.
Let's add a little key icon next to the ID.

Open up `IdField.php`. Ok, it sets its template name to `crud/field/id`. In the
template registry... here it is... that corresponds to this template right. So
the template that renders `IdField` is `Resources/views/crud/field/id.html.twig`.

So... let's override this! Instead of using the *first* method to do this - which
*would* work - let's do something different.

Copy this template and create our override template... which could live anywhere.
How about in `templates/admin/field`... and call it `id_with_icon.html.twig`.
Paste the contents... and I'll put a little icon right before this.

At the moment, this will *not* be used. To activate it globally, go to
`DashboardController.php`: you can configure template override paths down in
`configureCrud()`. Check it out: `->overrideTemplate()` where the first argument
is the *name* of the template - that's the thing you see inside `TemplateRegistry`
or `IdField`. Ok, so globally whenever EasyAdmin renders `crud/field/id`, we'll now
have it point to `admin/crud/field/id_with_icon.html.twig`.

How cool is that? Let's try it! Refresh and... whoops... let me get rid of my extra
`crud/` path. *Now* let's try it! And... yes! Awesome! We see the key icon across
the entire system.

## Re-Using the Parent Template

But we can make this even better. The id template is super simple... since it just
prints out the formatted value. But *sometimes* the original template might do more
complex stuff. Instead of repeating all of that in *our* template, we can *include*
the original template. So quite literally `include()`... and I'll start typing
`id.html.twig`... and let that autocomplete.

At the browser... we get the same result.

Next, let's talk about permissions: How we can deny access to entire CRUD controllers
or specific actions based on the user's role.
