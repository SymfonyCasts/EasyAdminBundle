# Fields

Okay, let's open up the "User's" section. EasyAdmin has a concept of fields. A field controls how a property is displayed on the index page, but also how it's displayed inside of a form. So the field completely defines that field inside the admin. By default, EasyAdmin just guesses which fields to include on the index page and form, but *usually*, you'll want to control this. How? Via the `configureFields()` method in the CRUD controller. In this case, open `UserCrudController.php`... and you can already see that it's so common, it has already generated a `configureFields()` inside of here. I'm going to uncomment that.

For now, you'll notice that you can either return an array or an iterable. I usually return an iterable by saying `yield Field::new()` and give it the property name `'id'`.

When I refresh... we have "ID" and nothing else.

In EasyAdmin, there are *many* different types of fields, such as text fields, boolean fields, and association fields, and it does its best to guess which type to use. In this case, you can't really see it, but when we said `'id'`, it's guessing that this is an `IdField`. So instead of just saying `Field::new()` and letting it guess, what I prefer to do is state the exact field type I want. Notice when I change it and refresh... that makes absolutely no difference because it was already guessing that this is an `IdField` type.

how do we figure out what all of the field types are? Documentation is the most obvious way. If you look on the web debug toolbar, there's a little EasyAdmin tool bar. Click into that and it will show you some basic information about what's going on. It also has a link to the document. I'll open this and keep it handy. It has a field section and down here, field types. So there's your big list of all of the different field types inside of EasyAdmin. *Or*, if you want, you can go directly to library, which is in `/vendor/easycorp/easyadmin-bundle/src/Field`. *Here* is the entire directory full of all of the different fields you can have. Let's add a few more fields inside of here.

If you look in the `User.php` entity, you can see `$id`, `$email`, `$roles`, `$password`, `$enabled`, `$firstName`, `$lastName`, `$avatar`, and then a couple of association fields. Let's add a couple more fields for some of those. I'll `yield TextField::new('firstName')`... repeat that for `$lastName`... and then for that `$enabled` field, I'm going to `yield BooleanField::new('enabled')`. Finally, I have a `$createdAt` field inside here, so I'll `yield DateField::new()`, and then my property is called `'createdAt'`. So I'm just listing the same properties that I have here inside of my entity. You don't see `$createdAt` here only because I'm getting it from this `TimestampableEntity` trait.

Anyways, with just this config, if we move over and refresh... beautiful! The text renders like a text, date knows how to handle date fields, and the boolean field gives us this nice little switch.

As a challenge, instead of rendering the "First Name" and "Last Name" columns, could we combine this into a single "Full Name" field? Let's try it!

I'll say `yield` `fullName` right here. This is not a real property. If I go over to `User.php`, the `$fullName` property does not exist. *But*, I do have a `getFullName()` method. So the question is: Will it be smart enough - because I have `'fullName'` here - to read this `getFullName()` method? And (I bet, you know the answer)... it does!

Behind the scenes, EasyAdmin uses the `PropertyAccessor` component of Symfony. It's the same component that's used inside of its form system, and it's really good at doing things like what we just did.

Back in `configureFields()`, I forgot to add an "email" field, so I'll say `yield TextField::new('email')`. And, no surprise, over here... it renders correctly. *But*, this is a case where, if you want, there's actually a more specific field for that: `EmailField`. The only difference it makes is that it renders with a link to each email. When you look at the form, it will now be rendering as an `<input type="email">`.

The real power of fields is that you can configure each one of these further. Some options for these are shared by all types. For example, you can call `->addCssClass()` to any field to add a CSS class to it. That's a super handy thing, *but* some options are specific to the field type itself, such as `BooleanField` has a `->renderAsSwitch()` method and we can pass this `false`. Now, instead of running this little switch, it just says "YES". This is probably a good idea because it was pretty easy to disable a user from this menu earlier.

This is great! We can control which fields are shown, *and* we know that there are methods you can call on each field object to configure its behavior. But remember, fields control both how things are rendered on the index page *and* how they're rendered on the form. Right now, if we go to the form... yeah. That's what I expected. These are the five fields that we've configured. It's not perfect, though. I *do* like having an "ID" column on my index page, but I do *not* like having an "ID" field in my form.

So next, let's learn how to *only* show certain fields on certain pages. We'll also learn a few more tricks for configuring them.
