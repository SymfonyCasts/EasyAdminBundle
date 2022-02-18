# Overriding Field Templates

We know that a field describes both the *form type* that you see on the form and
also *how* that field is rendered on the detail and index pages. We also know how
easy it is to customize the form type. We can `->setFormType()` to use
a completely different type or `->setFormTypeOption()` to *configure* that type.

We can *also* change a lot about how each *renders* on the detail and index pages.
For example, let's play with this "Votes" field:

[[[ code('8c316437ef') ]]]

If I autocomplete the methods on this, we have options like `->setCssClass()`,
`->addWebpackEncoreEntries()`, `->addHtmlContentsToBody()`, and `->addHtmlContentsToHead()`.
You can even call `->setTemplatePath()` to *completely* override how this field
is rendered on the index and detail pages, which we'll do in a moment.

But also notice that there's `->setTemplatePath()` *and* `->setTemplateName()`.
What's the difference?

## Template "Names" and the Template Registry

To answer that question, I'm going to hit `Shift` + `Shift` and open up a core class
from EasyAdmin called `TemplateRegistry.php`. If you don't see it, make sure to
"Include non-project items".

Perfect! Internally, EasyAdmin has *many* templates and it maintains this "map"
of template names to the template *filename* behind each. So when you call
`->setTemplateName()`, what you would pass is some *other* template name. For
example, I could pass `crud/field/money` if I wanted to use *that* template instead
of the normal one.

But you probably won't override the template *name* very often. Most of the time,
if you want to completely control how a field is rendered, you'll call
`->setTemplatePath()`.

Here's the plan: when "Votes" is rendered on the index and detail pages, I want
to render a *completely* different template. Let's call it
`admin/field/votes.html.twig`:

[[[ code('e35d52be8a') ]]]

Ok! Time to create that. In `templates/`, add 2 new directories `admin/field`...
and a new file: `votes.html.twig`. Inside, I don't really know what to put
here yet, so I'll just put "💯 votes!"... and see what happens:

[[[ code('71278b2d2c') ]]]

When we move over and refresh... there it is! We are now in *complete* control of
the votes!

## Digging into the Core Templates

But, if you're like me, you're probably wondering what we can *do* inside of here.
What variables do we have access to? One important thing to realize (and you can
see it here in `TemplateRegistry.php`) is that every single field has a corresponding
template. If you need to extend or change how a field is rendered, looking into
the *core* template is pretty handy.

For example, `votes` is an `IntegerField`. Whelp, there's a template called
`integer.html.twig`. Close this template registry and... let's go find that! Open
`vendor/easycorp/easyadmin-bundle/src/`... close up `Field/` and instead open
`Resources/views/crud/field`. *Here* is the list of *all* of the field templates
in the system. You can *also* see other templates that are used to render *other*
parts of EasyAdmin... and you can override these as well.

Open up `integer.html.twig`. Ok cool. Check out the collection of comments on
top. I like this! It helps our editor (and us) to know which
variables we have access to. Apparently, we have access to a `field` variable,
which is that familiar `FieldDto` object we talked about earlier. All the
integer template does is just... print `field.formattedValue`.

## Customizing the Template

Copy these three lines and paste them into our `votes.html.twig`:

[[[ code('c9da81e0b8') ]]]

Then instead of "💯 votes!", say `field.formattedValue` "votes".

And when we try this... beautiful! But I bet we can make this fancier!
If the votes are negative, let's put a little thumbs down. And if positive, a
thumbs up.

Take off the word "votes"... and add `if field.`. Hmm. What we want to get
is the *underlying* value - the true `integer`, not necessarily the "formatted" value.
We can get that by saying `field.value`.

So `formattedValue` is the *string* that would print on the page, while `value`
is the actual underlying (in this case) integer. So `if field.value >= 0`, `else`,
and `endif`:

[[[ code('65648439ec') ]]]

If it *is* greater than zero, add an icon with `fas fa-thumbs-up text-success`.
Copy that... and paste for our thumbs down with `text-danger`:

[[[ code('917380ae2b') ]]]

And... just like that, we're making this field render *however* we want. It doesn't
change how it looks like inside of the *form* (that's entirely handled by the form
type), but it *does* control how it's rendered on the index page, *and* the detail
page.

But, hmm. We also have a "votes" field inside of the Questions section. While it
would be pretty easy to also point *that* votes field to the same new template,
instead, I want to create a brand new *custom* field in EasyAdmin. That's next.
