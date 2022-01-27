# Field Rendering

As we discussed earlier, `configureFields()` controls how the fields are rendered on both the list page *and* the form pages. That leaves us with a situation that isn't exactly ideal. For example, we don't want an ID field on our form. We only want to render it on the index page.

To fix that, there are a bunch of really useful methods on these field classes we can utilize. For instance, we can call `->onlyOnIndex()`, and just like that, it's gone from the form page, but we still have it on the index page. As you're playing with these methods, I want to invite you to dive in and check out the code behind the scenes, because it will help you understand EasyAdmin better.

Methods like `->onlyOnIndex()` will give us tons of control, but also notice that `configureFields()` is passed the `$pageName`, which are things like "index", "detail", or "edit". So in the end, you can always just put `if` statements inside of here and dynamically return or *don't* return different things from this method. 

The other problem on our form is that we have this `fullName()` method. In the database, we have a "First Name" field and a "Last Name" field. It's kind of nice to be able to render them as a "Full Name" on the index page, but ultimately, when we go to the form, we'll probably want to control their first name and last name separately.

At the moment, this doesn't work. If I change something and then submit it, I get an error. It says "Could not determine access type for property "fullName"...". This is because, inside of our `User.php` class, I have `getFullName()`, but I don't have a `setFullName()` (and I don't really want one). The *point* is that over inside `configureFields()`, we can take that configure `fullName` and say `->onlyOnIndex();`. Now we'll have "Full Name" on our index, but we won't have "Full Name" on our form.

And actually, instead of `->onlyOnIndex()`, we can use `->hideOnForm()`. What's the difference? Using `->hideOnForm()` will still allow "Full Name" to show on the details page. If I go back to "Users" and click "Show" to go to the details page... there it is!. As you can see, there are *many* different ways to play with these fields to get them in just the right spot.

Now that Full Name is gone from the form, we need to put "First Name" and "Last Name" back. So say `yield Field::new('firstName')`. Copy this, paste below, and replace `firstName` with `lastName`. If we go refresh... looks good! Over on the list page... we *don't* want First Name and Last Name here, but now, you know what to do. There's a nice little method for this called `->onlyOnForms()`. I'll copy that and repeat it for `lastName`, and now... perfect!

Finally, I'll do the same thing for "Created At". I like having "Created At" on the list, but I do *not* like having "Created At" inside the form, because that's supposed to be set automatically. So, down here, I'll say `->hideOnForm();`. Beautiful.

Next, I want to dive a little further into fields. We're going to take one of these fields and configure its form type in a different way. As we do, we'll learn about a concept called Field Configurator.
