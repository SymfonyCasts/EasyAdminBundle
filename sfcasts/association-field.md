# Association Field

Coming soon...

Let's configure some fields for some of our other credit controllers, like go to
questions. So this is the default list that they added for us. So let's open
question, credit controller on comment, configure fields, and then I'm going to start
yielding some fields. I almost always yield the ID field, But then say only on index.
And then we'll yield a few more fields that are on our question, like field and I'll
use the lazy wear. I just use field colon new and let it guess the field type that's
really good enough unless you need to configure something that's specific to a field
type. So I'll copy that. I'm gonna pay this at two more times for votes and also
created that. And for this one, I'll say hide on form When we refresh very nice. Now
there are a number of things that you can configure on these fields, and we've
already seen a, a lot of them. If you just use the audit completion here, there are
tons of things, CSS classes, um, uh, permissions. We'll talk about later. There's
also a way you can control the label right now, the label and votes is votes, which
makes sense. But we could also say set label, total votes.

We're actually in this case, this, the, label's also the second argument to the new
method. So we can even keep this a little bit shorter here and just pass it right
there. And that works perfectly. But I think these numbers would look a little better
if they were right aligned. So that is of course, Another method we can use set, text
align, right? To get those numbers to go to the right. So just examples of all the
crazy things that you can do when you configure the fields. And as we know, inside of
a specific field class, there often methods that are special to it.

All right. So over on question, let's edit one of these questions. So not
surprisingly, it let's just name in total votes, but our question entity has several
other fields on it that we want specifically. There's the question itself. This is
like gonna be a text area field. There's also an asked by user field and every
question has one topic, which is also a relationship. So back on our question, credit
controller, let's first add a text area for our question. So for this, there is a
surprise text area field, a tech area, field, new question. And then I'm gonna say
hide on index because that's a really big field. So I definitely don't want that to
show up on the list, but on the form. Excellent.

So the next field I wanna put up here is this topic field, which is an association.
It's a relationship. How do we handle that in easy admin with a very nice association
field yield association, field Kwan, new and past topic. And that's it. So if you
click questions to go back to the index page that shows up, but it's not very
descriptive, it's just topic. And then the ID of that topic. And if you click to edit
a question, it explodes object of class topic could not be converted to a string. So
both on the index page and on the form, it's trying to find a, a string
representation of our topic on the index page. It guesses by using the ID on the
form. It just explodes. So to fix this, the easiest way to fix this is to go into our
topic entity and add a two string method.

So I'll scroll down a little bit and after construct method, public
function_underscore two string, which will return a string and I'll return this->name
now in refresh. We got it. And wow, this is awesome. We got this really cool select
element with a search on it. Absolutely for free. Now, the important thing to know
about this is that it's really just a select element that's made to look extra fancy
when you're typing into here. There's no Aja calls being made. All of the topics are
loaded onto this page in the HTML. And then this, this is just a little JavaScript
widget to help you select them over on the index page. For question, our two string
method gives us a better text here, and it's really nice cause we now have a link to
jump right to

Right to that topic.

The only problem is that if you click this link, it actually goes to the detail
action of the topic credit controller, which we explicitly disable. So probably not
something it's prob you're probably not going to disable the detail action on a real
project. So I'm not going to worry about this, but there is a little bug and easy
admin where it doesn't check the permissions correctly on this, and always generates
the link. Even if you can't get to it. Anyways, let's repeat this association field
thing also for the asked by field on question, which is another relationship to user.
So over here in my controller, down near the bottom, cause it's less important. I'll
say association, field, colon, colon knew how. And then of course, as soon as we do
that, it will show up kind of boring on this form. We'll get an error over here, cuz
we need to add a two string method to our user class. So let's pop open user. Um,
I'll scroll up after my instructor of a function_underscore under score, two string
returns, a stringing and let's return this arrow. Get full name Back over here.
Beautiful. You can see it down here on the bottom. It works awesome though. It is
completely fine. Technically I'm a little tight on the bottom. It's kind of hard for
me to see this entire thing. So I'm at a little margin bottom to this page, which we
can do very easily now. Cause we have the assets styles, admin dot C S file. So let's
see here. Let me do a little bit of digging. There is

A section up here called main content. That's kind of like this entire area this
time. I'm not gonna like override any CSS variables or anything. I'm just gonna
target main content, add a little bottom margin. So dot main content margin, bottom
hundred pixels and that gonna refresh

It gives us a little bit of breathing room down there. I actually needed a force
refresh, which makes so easier to use this. Now one issue with these fields is, as I
mentioned, they're really just fancy looking select fields, which means that all the
users in our database are actually being rendered into the HTML right now watch, if I
go to view source view, page source in search Ticia you can see that in the HTML, it
loaded all of these options right onto the page. So if you only have a few users or
you only have a few topics, no big deal, but in a real application, we're gonna have
hundreds or thousands or maybe millions of users. We cannot load them onto the entire
this page. Well break things. Let's fix that next.