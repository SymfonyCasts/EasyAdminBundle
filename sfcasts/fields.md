# Fields

Coming soon...

Okay, let's open up the user's section. Easy admin has a concept of fields, a field
controls, how a property is displayed on the index page, but also how it's displayed
inside of a form. So the field completely defines it completely defines that field
inside the admin by default, easy admin, just guesses, which fields to include on the
index page and on the form. But usually you'll want to control this how via the
configure fields method in the credit controller. So in this is open user credit
controller and you can actually already see it's so common that it generates a
configure fields inside of here. So I'm gonna uncommon that. And for now you notice
that you can either return an array or you can return an Iterable. I usually return
an ITER role by saying yield. So right now I'm just going to one field. I'm gonna
say, yield field, colon new. That's how you create a new field. And here you put like
the property name for that. So ID all right. When I refresh, we have ID and nothing
else.

Now there in easy admin, there are many, many different types of fields like text
fields, uh, boo fields and association fields. And it does its best to guess which
type to use. So in this case, you can't really see it. But when we said ID, it is
guessing that this is an ID field. So instead of just saying field, call new and
letting it guess what I usually do is tell the exact field type I want. So you'll
notice here when I change it in refresh. That makes absolutely no difference because
it was already guessing that this is an, an ID field type.

So how do we figure out what all the field types are? Well, of course, documentation
is one way. If you look on the web deal of a toolbar, there's actually a little easy
admin tool bar. You can click into that and show you some basic information about
what's going on and has a link to the document. So I'm gonna open this and kind of
keep it handy inside of here. It has a field section and down here, field types. So
there is your big list of all of the different field types inside of easy admin. Or
if you want, you can go directly and look at the library. So vendor easy Corp, easy
admin bundle source field, and here is the entire directory full of all of the
different fields you can have. So let's add a few more fields inside of here. So if
you look in the user entity, you can see of ID, email roles, password enabled, first
name, last time avatar, and then a couple of association fields. So let's add a
couple more fields for some of those. So I'm going to yield a text field, one of the
most basic fields, call on call new for first name,

Repeat that for last name. And then for that enabled field, I'm going to yield a buoy
field, new enabled. Then finally I have a created at field inside of there. So I'll
yield a date, field calling new, and then it's called my property is called created
at so I'm just listening to same properties that I have here inside of my I entity.
You don't see created ad only because I'm getting it from this timetable entity trait

Anyways, with just this config here, if we move over and refresh beautiful. So the
text renders like a text date knows how to handle date fields and the buoyant field
gives us this nice little, uh, switcher. So as a challenge instead of rendering the
first name and the last name columns, could we combine this into a single full name
field? Well, let's try it. I'm gonna say yield full, full name inside of here. Now
this is not a real property. If I go over to user, there is no full name property,
but I do have inside of here a get full name method. So the question is, is it gonna
be, be smart enough because I have full name here to read this, get full name method.
And I bet, you know, the answer it does behind the scenes, easy admin uses the
property accessor component of Symfony. It's the same component that's used inside of
its form system. And it's really good at doing things like you giving it full name
and it figuring out that it can access it via a get full name method. So back in
configure fields,

I,

One field I forgot to put was email. So I'm gonna yield text, field, call, new email,
and no surprise over here. It renders correctly, but this is a case where if you
want, there's actually a more specific field for that. It is email field. And the
only difference it makes is it renders with a link to that. And also if we look at
the form

And when you look at the form, it's now gonna be rendering as an input type = email.
Now the real power of the fields is that you can configure each one of these further.
Some options on these are shared by all types. Like for example, you can call arrow,
add CSS class to any field, to add a CSS class to it. That's a super handy thing, but
some options are specific to the field type itself. For example, boo field has a
render as switch method and we can pass this false. What this tells it to do is
instead of running the school a switch thing, it's not, I'm just going to say yes,
which is probably a good idea because it was pretty easy to disable a user from this,
um, menu a few seconds ago. So this is great. We can control which fields are shown.
And we know that there are methods you can call on each field object to configure its
behavior. We're remember fields control, both how things are rendered on the index
page and how they're rendered on the form. So right now, if we go to the form, yeah,

That's what I expected. These are the five fields that we've configured though. It's
not perfect for one. I do like having an ID column on my index page, but I do not
like having an ID field in my form. So next let's learn how to show certain fields
only on certain pages and a few more tricks for configuring them.

