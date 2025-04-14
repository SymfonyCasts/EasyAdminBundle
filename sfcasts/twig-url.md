# Linking to EasyAdmin from Twig

Let's go look at the "Show" page for a question that is *not* approved yet. We have
a lot of buttons up here... which is fine. But what if we don't like their order?
Like, Delete might make more sense as the last button instead of in the middle.

## Ordering Actions

No problem! We can control this inside of `configureActions()`. At the bottom,
after we've set up the actions, call another method - `->reorder()` - and pass
this the page that we want to reorder them on. In this case, it's `Crud::PAGE_DETAIL`.
Then, very simply, add the names of the actions. We have quite a few... let's
start with `approve`, `view`... and then the three built-in actions: `Action::EDIT`,
`Action::INDEX`, and `Action::DELETE`. These are the five actions that correspond
to these five buttons.

[[[ code('ae34e09ff5') ]]]

## Adding Action Icons to the Entire Admin

Now when we refresh... very nice! Though... I'm noticing that it looks a bit
odd that some of these have icons and others don't. Let's see if we can add an icon
to the Edit and Index actions across our *entire* admin.

If we want to modify something for all of our admin, we need to do it inside of
`DashboardController`. As we saw earlier, to modify a *built-in* action,
we can call the `->update()` function. Pass this the page - `Crud::PAGE_DETAIL` -
the action - `Action::EDIT` - and then a `static function` with an `Action $action`
argument. Inside, modify and return the `Action` at the same time:
`return $action->setIcon('fa fa-edit')`.

[[[ code('a2239b75a1') ]]]

Let's do the same thing one more time for the index action button: use
`Action::PAGE_INDEX`... and we'll give this `fa fa-list`.

[[[ code('cbe93bfa5f') ]]]

Refresh now and... I love it! We see the icons here... and if we go anywhere
else - like to an Answer's detail page - the icons are here too.

## Adding a Link to the Admin From Twig

At this point, we know how to generate a link to any EasyAdminBundle page. If I
scroll up a bit... the key is to get the `AdminUrlGenerator`, and then set whatever
you need on it, like the action and CRUD controller.

Go to the Homepage... and click into a question. To make life easier for admin users,
I want to put an "Edit" button that takes us directly to the edit action for this
specific question. So... how do we generate URLs to EasyAdmin from *Twig*?

Open the template for this page - `templates/question/show.html.twig` - and find
the `<h1>`. Here it is. For organization, I'll wrap this in a `<div>` with
`class="d-flex justify-content-between"`.

[[[ code('15b2c88dbc') ]]]

After the `h1`, add the link... but only for admin users. So
`{% if is_granted('ROLE_ADMIN') %}`... and `{% endif %}`. Inside `<a href="">` -
I'll leave the `href` empty for a moment - with `class="text-white"`. And
inside of *that*, a `<span class="fa fa-edit">`.

[[[ code('61a82c2b5f') ]]]

Back in our browser, try that. And... hello edit link!

To generate the URL, we need to tell EasyAdmin which CRUD controller, action, and
entity ID to use... all stuff we've done in PHP. In Twig, it's *nearly* the same
thing thanks to a shortcut function called `ea_url()`.

*This* gives us the `AdminUrlGenerator` object. And so, we can just... call the
normal methods, like `.setController()`... passing the *long* controller class
name. We have to use double slashes so that they don't get escaped, since we're
inside of a string. Now add `.setAction('edit')` and `.setEntityId(question.id)`.

```note
In a modern EasyAdmin version, you can generate URLs normally through the Router object
`{% set path = path('admin_question_edit', {entityId: question.id}) %}`
```

[[[ code('c0a56ba5a3') ]]]

It's a little weird to write this kind of code in Twig, but that's how it's done!
Back over here, refresh... and try the button. Got it!

Ok team, last topic! Let's talk about how we can leverage layout panels and other
goodies to organize our form into groups, rows, or even tabs on this form page.
