# Field Rendering

Coming soon...

As we have talked about the configure fields controls both how the fields are
rendered on the list page, but also how they're rendered on the form pages. And that
leaves us with a situation that is not for example, we don't want an ID field on our
form. We only wanna render it on the index page. So to fix that we can call there's a
bunch of really useful methods on these field of classes. For example, we can call
only on index And just like that it's gone from the form page, but we still have it
on the index page. And as you're playing with these methods, I want to invite you to
kind of dive in and see the code behind the scenes. That's gonna help you understand
easy admin better. So for example, if I held command or control and click into only
index jumps us into this thing called field trait, this is a, a trait that's shared
by all fields. And you can see something like this arrow, DTO->set displayed on, You
know what no, what let's not show that.

So methods like only on index are gonna give us lots of control, but also notice that
configure fields is past the page name. That'll be like index or detail or edit. So
at the end of the day, you can always just put if statements inside of here and, uh,
dynamically return or don't return different things from this method. All right. So
the other problem on our form is that we have this full name method in the database.
We have a first name, field, and a last name field. So it's kind nice to be able to
render them as a full name on the index page. But ultimately when we go to the form,
we probably wanna control their first name in last name separately. And this won't
even work if I submit this right now. Well actually, if I change something and then
submit it, I get an error. It says, can I determine access type for property full
name? Because inside of our user class, I have a get full name, but I don't have a
set full name and I don't want one. So the point is that over inside a configure
fields, we can take that configure full name and say, only on index. Now we're gonna
have full name on our index, but we don't have full name on our form.

And actually instead of only on index, we can instead use hide on form. The
difference being that full name will now show on the details page. So if I go back to
users and click show to go to the details, page full name is still included on there.
So you can see there's lots of different ways to play with these fields to get 'em in
just the right spot. All right. So now that full name's gone from the form. We need
to put first name and last name back. So let's say yield and I'll just use the lazy
field call, call new way.

First name, last name that we'll get it to show up here perfectly then back on the
list page, we don't want those. You guys know what to do. So there's a nice little
method for this called only on forms. So I'll copy that and repeat that for last name
and now perfect. Then finally, I'm gonna do the same thing for created at I like
having created at, on the list. I do not like having created at inside of the form
because that's really supposed to be set automatically. Sit down here. We will say
hide on form, be beautiful. So that was fairly easy and fairly simple. Next, I wanna
dive a little bit further into the fields. We're gonna take one of these fields and
configure its form type in a different way. Now, as we do, we're gonna learn about a
concept called field configurator.

