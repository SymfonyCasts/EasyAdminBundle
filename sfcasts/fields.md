# Configuring Fields

Open up the "Users" section. EasyAdmin has a concept of _fields_. A field
controls how a property is displayed on the index and detail pages, but *also* how
it renders inside of a form. So the field *completely* defines the property inside
the admin. By default, EasyAdmin just... guesses which fields to include.
But *usually* you'll want to control this. How? Via the `configureFields()` method
in the CRUD controller.

In this case, open `UserCrudController.php`... and you can see that it already has
a commented-out `configureFields()` method:

[[[ code('725507eea4') ]]]

Go ahead and uncomment that.

Notice that you can either return an `array` or an `iterable`. I usually
return an `iterable` by saying `yield Field::new()` and passing the property name,
like `id`:

[[[ code('08a39ad7cc') ]]]

When I refresh... we have "ID" and nothing else.

## Field Types

So EasyAdmin has *many* different *types* of fields, like text fields, boolean fields,
and association fields... and it does its best to guess which type to use. In this
case, you can't really see it, but when we said `id`, it guessed that this is
an `IdField`. Instead of just saying `Field::new()` and letting it guess, I often
prefer being explicit: `IdField::new()`:

[[[ code('1055926db3') ]]]

Watch: when we refresh... that makes absolutely no difference! It was *already*
guessing that this was an `IdField`.

Cool! So how do we figure out what all of the field types are? Documentation is the
most obvious way. If you look on the web debug toolbar, there's a little EasyAdmin
icon. Click into that... to see some basic info about the page... *with*
a handy link to the documentation. Open that up. It has a "Field Types" section down
a ways. Yup, there's your big list of all the different field types inside of
EasyAdmin.

*Or*, if you want to go rogue, you find this directly in the source code. Check out
`vendor/easycorp/easyadmin-bundle/src/Field`. *Here* is the directory that holds
*all* the different possible field types.

Back in our CRUD controller, let's add a few more fields.

If you look in the `User` entity, you can see `$id`, `$email`, `$roles`,
`$password`, `$enabled`, `$firstName`, `$lastName`, `$avatar`... and then a couple of
association fields:

[[[ code('7825749870') ]]]

We won't need to manage *all* of these in the admin, but we *will* want most of them.

Add `yield TextField::new('firstName')`... repeat that for `$lastName`... and then
for the `$enabled` field, let's `yield BooleanField::new('enabled')`. We also have
a `$createdAt` field... so `yield DateField::new('createdAt')`:

[[[ code('b5b8200b8b') ]]]

So I'm just listing the same properties that we see in the entity. Well, we don't
see `$createdAt`... but that's only because it lives inside of the
`TimestampableEntity` trait:

[[[ code('4cbff85a5b') ]]]

Anyways, with just this config, if we move over and refresh... beautiful! The text
fields render normal text, the `DateField` knows how to print dates and the
`BooleanField` gives us this nice little switch!

## Using "Pseudo Properties"

As a challenge, instead of rendering "First Name" and "Last Name" columns, could
we combine them into a single "Full Name" field? Let's try it!

I'll say `yield TextField::new('fullName')`:

[[[ code('d38ae751b7') ]]]

This is *not* a real property. If you open `User`, there is *no* `$fullName`
property. *But*, I do have a `getFullName()` method:

[[[ code('3b2d313b17') ]]]

So the question is: is it smart enough - because the field is called
`fullName` - to call the `getFullName()` method?

Let's find out. I bet you can guess the answer. Yup! That works!

Behind the scenes, EasyAdmin uses the PropertyAccess Component from Symfony.
It's the same component that's used inside of the form system... and it's *really*
good at reading properties by leveraging their getter method.

## Field Options

Back in `configureFields()`, I forgot to add an "email" field. So,
`yield TextField::new('email')`:

[[[ code('c897c2e6cd') ]]]

And... no surprise, it renders correctly. *But*, this is a case where there's
actually a more *specific* field for this: `EmailField`:

[[[ code('7f7a82619e') ]]]

The only difference is that it renders with a link to the email. *And*, when
you look at the form, it will now be rendering as an `<input type="email">`.

The *real* power of fields is that each has a *ton* of options.
Some field options are shared by *all* field types. For example, you can call
`->addCssClass()` on any field to add a CSS class to it. That's super handy.
But *other* options are specific to the field *type* itself. For example,
`BooleanField` has a `->renderAsSwitch()` method... and we can pass this `false`:

[[[ code('cd2f9d6813') ]]]

Now, instead of rendering this cute switch, it just says "YES". This... is probably
a good idea anyways... because it was a bit *too* easy to accidentally disable a user
before this.

So... this is great! We can control which fields are displayed *and* we know that
there are methods we can call on each field object to configure its behavior. But
remember, fields control both how things are rendered on the index and detail pages
*and* how they're rendered on the *form*. Right now, if we go to the form... yup!
That's what I expected: these are the five fields that we've configured.

It's not perfect, though. I *do* like having an "ID" column on my index page, but
I do *not* like having an "ID" field in my form.

So next, let's learn how to *only* show certain fields on certain pages. We'll also
learn a few more tricks for configuring them.
