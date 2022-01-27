# Fields on some Pages, not Others

As we discussed earlier, `configureFields()` controls how each field is rendered on
both the list page *and* the form pages. That leaves us with a situation that...
isn't exactly "ideal". For example, we don't want an ID field on our form. But I
*do* like having it on the index page!

To fix this, there are a bunch of useful methods on these field classes that we can
utilize. For instance, we can call `->onlyOnIndex()`. And... just like that, it's
gone from the form page, but we still have it on the index page. As you're playing
with these methods, I invite you to be curious: dive in and check out the code behind
the scenes. It's a *great* way to learn more about how EasyAdmin works on a deeper
level.

Methods like `->onlyOnIndex()` give us a lot of control. But also notice that
`configureFields()` is passed the `$pageName`, which will be a string like "index",
"detail", or "edit". So in the end, you can always just put `if` statements inside
of this method and conditionally yield - or *don't* yield - different fields.

## Hiding on the Form

The other problem on our form is that we have this Full Name field. In the
database, we have `firstName` and `lastName` fields. It *is* kind of nice to
render them as "Full Name" on the index page. But ultimately, when we go
to the form, we really need *separate* `firstName` and `lastName` fields.

And, at the moment, this doesn't even work! If I change something on the form and
submit... error! It says:

> Could not determine access type for property "fullName"...".

This is because, inside of our `User` class, we have a `getFullName()` method, but
we do *not* have a `setFullName()` method (and I don't really want one). The *point*
is that, over inside `configureFields()`, we need to change `fullName` to
render `->onlyOnIndex();`. Now we'll have "Full Name" on our index, but we won't
have one on the form.

And actually, instead of `->onlyOnIndex()`, we can use `->hideOnForm()`. What's the
difference? Using `->hideOnForm()` still allows "Full Name" to show on the
detail page. If I go back to "Users" and click "Show"... there it is!.

Now that "Full Name" is gone from the form, let's put "First Name" and "Last Name"
*back*. So, `yield Field::new('firstName')`... copy this, paste, and replace
`firstName` with `lastName`. If we refresh... looks good! Over on the list page...
looks weird! We *don't* want those here.

But now, we know what to do. There's a nice method for this: `->onlyOnForms()`. Copy
that, repeat it for `lastName` and now... perfect!

Finally, let's do something similar for "Created At". I like having this on
the list, but I do *not* like having it inside the form because it should be
set automatically. So, down here, add `->hideOnForm()`.

Beautiful!

Next, I want to dive a bit further into fields. We're going to take one of these
fields and configure its *form type* in a different way. As we do, we're going to
accidentally learn about an important concept called field configurators.
