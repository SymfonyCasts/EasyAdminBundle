# The AssociationField

Let's configure fields for some of our other CRUD controllers. Go to the Questions
page. This shows the default field list. We can do better. Open
`QuestionCrudController`, uncomment `configureFields()`, and then... let's yield
some fields! I'm going to write that down in my poetry notebook.

Let's yield a field for `IdField`... and call `->onlyOnIndex()`. Then
`yield Field::new('name')`.

I'm being lazy... where I just use `Field::new()` and let it guess the field
type. That *should* be good enough unless you need to configure something that's
*specific* to a field type.

Copy that... and paste this two more times for `votes` and `createdAt`. For
`createdAt`, don't forget to add `->hideOnForm()`.

Cool! Find your browser, refresh and... very nice!

## More Field Configuration

There are a *bunch* of things that we can configure on these fields, and we've
already seen several of them. If you check the auto-completion, wow! That's a
great list: `addCssClass()`, `setPermission()` (which we'll talk about later), etc.
We can also control the field *label*. Right now, the label for votes is...
"Votes", which makes sense. *But* we could say `->setLabel('Total Votes')`.

Or, because "label" is the second argument to the `new()` method, we could
shorten this by passing it there.

And... that works perfectly! *But*, I think these numbers would look a little better
if they were right-aligned. That is, *of course*, another method we can call:
`->setTextAlign('right')`. This... yup! Scooches the numbers to the right!

These are just a *few* examples of the crazy stuff you can do when you configure
each field. And of course, each field class has even *more* methods that are
specific to it.

Back on the question section, let's edit one of these. Not surprisingly, it
just lists "Name" and "Total Votes". But our `Question` entity has several other
fields that we want here. Specifically, the `$question` itself and `$askedBy`
and `$topic` which are both relationships.

Back in `QuestionCrudController`, the `question` field will hold a lot of text,
so it should be a textarea. For this, there is a (surprise!) `TextareaField`. Yield
`TextareaField::new('question')`... and then `->hideOnIndex()`... because
we definitely don't want a giant block of text in the list.

Back on the form... excellent!

## Hello AssociationField

Let's add the `$topic` field, next. This is interesting because it's a *relation*
to the `Topic` entity. How can we handle that in EasyAdmin? With the *super*
powerful `AssociationField`. Yield `AssociationField::new()` and pass `topic`.
That's it!

Click "Questions" to go back to the index page. Hmm. We *do* have a "Topic" column,
but it's not very descriptive. It's just "Topic" and the ID of that topic. And if
you click to edit a question, it explodes!

> Object of class App\Entity\Topic could not be converted to string

On both the index page *and* on the form, it's trying to find a string representation
of the `Topic` object. On the index page, it guesses by using its id. But on the
form... it just explodes. The easiest way to fix this is to open the `Topic`
entity and add a `__toString()` method.

Scroll down a bit... and, after the `__construct` method, I'll add
`public function __toString()`, which will return a `string`. Inside
`return $this->name`.

Now when we refresh... got it! And check it out! It renders a really cool select
element with a search bar on it. For free? No way!

The important thing to know about this is that it's really just a `select` element
that's made to look and work fabulously. But when you type, no AJAX calls are
made to build this list. *All* of the possible topics are loaded onto this page in
the HTML, and then this JavaScript widget helps you select them.

Over on the index page for Questions, our `__toString()` method gives us better text.
And EasyAdmin even renders a to jump right *to* that Topic.

The only problem is that, when we click the link, it's broken! It goes to the "detail"
action of `TopicCrudController`... which we *disabled* earlier. Whoops. In a real
app, you probably *won't* disable the "detail" action... it's pretty harmless. So
I'm not going to worry about this. But you *could* argue that this is a *tiny* bug
in EasyAdmin where it doesn't check the permissions correctly before generates the
link.

Anyways, let's repeat this `AssociationField` for the `$askedby` property in
`Question`, which is *another* relationship. Over here in the controller,
down near the bottom... because it's less important...
`yield AssociationField::new('askedBy')`.

As *soon* as we do that, it shows up the index page... but just showing the id...
and on the form, we get the same error as before. No problem. Pop open `User`...
I'll scroll up, then add `public function __toString: string`. Inside
`return $this->getFullName()`.

Back over on the form... nice! It's way at the bottom, but works great!

## Adding some Field Margin

Even so, there's not much space at the bottom of the page... so it's hard to see
the entire list of users. Let's add some "margin-bottom" to this page. We
can do that *very* easily now thanks to the `assets/styles/admin.css` file.

Let's do some digging. Ah! There's a section up here called "main-content", which
holds this entire body area. This time, instead of overriding a CSS property -
since there *is* no CSS property that controls the bottom margin for this element,
we can do it the normal way. Add `.main-content` with `margin-bottom: 100px`.

Let's check it! Refresh. Ah, that's better! We have some breathing room at the
bottom. If the change didn't show up for you, try a force refresh.

The `AssociationField` is *great*. But ultimately, what it renders is just a
fancy-looking `select` field... which means that *all* the users in the entire
database are being rendered into the HTML right now. Watch! I'll view the page source,
and search for "Ticia". Yup! The server loaded *all* of these options onto the page.
If you only have a few users or topics, no biggie!. But in a real app, we're going
to have hundreds, thousands, maybe even millions of users, and we *cannot* load all
of those onto the the page. That will absolutely break things.

No worries: `AssociationField` has a trick up its sleeve.
