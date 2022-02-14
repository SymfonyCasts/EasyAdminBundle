# The AssociationField

Let's configure the fields for some of our other CRUD controllers. Go to the "Questions"
page. This shows the default field list. We can do better. Open
`QuestionCrudController`, uncomment `configureFields()`, and then... let's yield
some fields! I'm going to write that down in my poetry notebook.

Let's yield a field for `IdField`... and call `->onlyOnIndex()`. Then
`yield Field::new('name')`:

[[[ code('63b9f67ad2') ]]]

Yea, yea... I'm being lazy. I'm using `Field::new()` and letting it guess the field
type for me. This *should* be good enough most of the time, unless you need to
configure something *specific* to a field type.

Copy that... and paste this two more times for `votes` and `createdAt`. For
`createdAt`, don't forget to add `->hideOnForm()`:

[[[ code('3f04221eef') ]]]

Cool! Find your browser, refresh and... good start!

## More Field Configuration

There are *a lot* of things that we can configure on these fields, and we've
already seen several. If you check the auto-completion, wow! That's a
great list: `addCssClass()`, `setPermission()` (which we'll talk about later) and
more. We can also control the field *label*. Right now, the label for votes is...
"Votes". Makes sense! But we can change that with `->setLabel('Total Votes')`.

Or, "label" is the second argument to the `new()` method, so we could shorten
this by passing it there:

[[[ code('c4c62153ce') ]]]

And... that works perfectly! But I think these numbers would look better
if they were right-aligned. That is, *of course*, another method:
`->setTextAlign('right')`:

[[[ code('d124992a3f') ]]]

This... yup! Scooches our numbers to the right!

These are just a *few* examples of the crazy things you can do when you configure
each field. And of course, many field classes have *more* methods that are
specific to them.

Back on the question section, let's edit one of these. Not surprisingly, it
just lists "Name" and "Total Votes". But our `Question` entity has more
fields that we want here, like the `$question` text itself... and `$askedBy`
and `$topic` which are both relationships:

[[[ code('7d511510d0') ]]]

Back in `QuestionCrudController`, the `question` field will hold a lot of text,
so it should be a textarea. For this, there is a (surprise!) `TextareaField`. Yield
`TextareaField::new('question')`... and then `->hideOnIndex()`:

[[[ code('31918b86f1') ]]]

Because we definitely do *not* want a wall of text in the list.

Back on the form... excellent!

## Hello AssociationField

Let's do the `$topic` field! This is an interesting one because it's a *relation*
to the `Topic` entity. How can we handle that in EasyAdmin? With the *super*
powerful `AssociationField`. Yield `AssociationField::new()` and pass `topic`:

[[[ code('b2b1d50dcb') ]]]

That's it!

Click "Questions" to go back to the index page. Hmm. We *do* have a "Topic" column,
but it's not very descriptive. It's just "Topic" and then the ID. And if
you click to edit a question, it explodes!

> Object of class `App\Entity\Topic` could not be converted to string

On both the index page *and* on the form, it's trying to find a string representation
of the `Topic` object. On the index page, it guesses by using its id. But on the
form... it just... gives up and explodes. The easiest way to fix this is to open
the `Topic` entity and add a `__toString()` method.

Scroll down a bit... and, after the `__construct` method, add
`public function __toString()`, which will return a `string`. Inside
`return $this->name`:

[[[ code('bef14e0720') ]]]

Now when we refresh... got it! And check it out! It renders a really cool select
element with a search bar on it. For free? No way!

The important thing to know about this is that it's really just a `select` element
that's made to look and work fabulously. But when you type, no AJAX calls are
made to build the list. *All* of the possible topics are loaded onto the page in
the HTML. And *then* this JavaScript widget helps you select them.

And over on the index page for Questions, our `__toString()` method now gives
us better text in the list. And EasyAdmin even renders a link to jump right *to*
that Topic.

The only problem is that, when we click, it's busted! It goes to the "detail"
action of `TopicCrudController`... which we *disabled* earlier. Whoops. In a real
app, you probably *won't* disable the "detail" action... it's pretty harmless. So
I'm not going to worry about this. But you *could* argue that this is a *tiny* bug
in EasyAdmin because it doesn't check the permissions correctly before generating
the link.

Anyways, let's repeat this `AssociationField` for the `$askedby` property in
`Question`, which is *another* relationship. Over in the controller, down near
the bottom... because it's less important... `yield AssociationField::new('askedBy')`:

[[[ code('8c63712fd1') ]]]

As *soon* as we do that, it shows up the index page... but just with the id...
and on the form, we get the same error. No problem. Pop open `User`...
I'll scroll up, then add `public function __toString(): string`... and
`return $this->getFullName()`:

[[[ code('92c7b7866c') ]]]

Back over on the form... nice! It's way at the bottom, but works great!

## Adding some Field Margin

Well, it's *so* far at the bottom that there's not much space! It's hard to see
the entire list of users. Let's add some "margin-bottom" to the page. We
can do that *very* easily now thanks to the `assets/styles/admin.css` file.

Let's do some digging. Ah! There's a section up here called `main-content`, which
holds this entire body area. This time, instead of overriding a CSS property -
since there *is* no CSS property that controls the bottom margin for this element -
we can do it the normal way. Add `.main-content` with `margin-bottom: 100px`:

[[[ code('d7a6bc1012') ]]]

Let's check it! Refresh. Ah, that's much better! If the change didn't show up for
you, try a force refresh.

Ok, the `AssociationField` is *great*. But ultimately, what it renders is just a
fancy-looking `select` field... which means that *all* the users in the entire
database are being rendered into the HTML right now. Watch! I'll view the page source,
and search for "Tisha". Yup! The server loaded *all* of the options onto the page.
If you only have a few users or topics, no biggie!. But in a real app, we're going
to have hundreds, thousands, maybe even millions of users, and we *cannot* load all
of those onto the page. That will absolutely break things.

But no worries: the `AssociationField` has a trick up its sleeve.
