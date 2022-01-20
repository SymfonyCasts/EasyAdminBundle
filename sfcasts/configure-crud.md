# Configure Crud

Coming soon...

If you look at the index page of any of these crud, it doesn't sort by default. It
just lists them. However they come out of the database. It would be cool if we could
control how, which column they sorted by just when we click on them, this is a
setting on the crud itself. So as a reminder, if I go to one of my crud controllers
and open its base class, there are a number of different methods inside of here that
we can override to configure things. The main ones are really configuring the crud
where crud controls things about the entire. So like things about the entire answers,
crud, configure actions, controls, which actions are linked from which pages. And of
course, configure fields con controls the fields that show up and the crud is
something that can be configured F at once for all crud by configuring this inside of
our dashboard on, or only on one specific crud. So let's change these sort globally
across our entire admin to sort by ID ascending, to do that. We are going to want to
go into our dashboard anywhere inside of here, I'll go to co generate or command N on
a Mac hit overrides methods and override configure crud.

Then this is a number of methods on them. One of them is called set default sort,
pass that in array of the things we wanna sort by. We wanna sort by ID descending.
Now back over, when we click on the questions thing beautiful, you can see by
default, it is sorting by ID. And if I click any of these, they're all sorting by ID
descending. I need to fix that text earlier, but what if we wanna sort questions in a
different way by default or even sort them across a relationship? So every question
is owned by a user. What if we wanted to actually show the questions whose users are
enable old first? How can we do that? Well, first, since we want to only apply this
to the question, we need to do it inside of our question crud. So inside of here,
I'll go to the bottom, same thing, we'll override and figure crud and call the exact
same methods before set default sort. So override the one on our dashboard in here.
We can say, ask by. So that's the relationship from question to user and then dot
enabled. So this is actually referencing the enabled property on user, and we can set
that to descending. And then also, uh, a in addition to that, once they're sorted by
the enabled first we'll will then sort them by created that descending.

So now when we click on the questions link, because we're sorting across multiple
fields, you don't see any of these, like one thing selected, but it looks right
created at seems like it's first. And if we on here and I'll click to open up the, on
the web Depot toolbar, the database section, and on here, I'm gonna search for on the
queries for search for order by Perfect. There it is right here. So it's inside the
view format. The query, you can see it is ordering by the, our user dot enabled and
then are created at pretty cool.

So in addition to the default, sorting the user can of course come in here and sort
by whatever column they want, but sometimes sorting for a specific field. Doesn't
really make sense. You can see that it's already disabled for answers. And if we went
over to the user Cru, you can see there's no sort for the avatar, which would make no
sense. If you wanna control this a bit further, you can like, let's pretend for some
reason, we don't want people sorting by the name of, of the question. This is
actually something that you configure on the field itself. So in question credit
controller for our name, field set, sortable false. Yep. And you can see the error
just disappears. Let's open the topic index page. This entity is really simple and
there's not a lot that we can improve here. But one thing we can do is we can render
these actions, which are normally sort of under this dropdown. We can render them in
line. Since we have some more space on the page To do that, of course, we're gonna
head to topic a crowd controller, and this is also something that you can configure
on the crud. So I'll go down here and I'll say it, uh, override, configure, crud,

And say, show entity actions in line dropdown is the default and now super cool.
There are a few other ways to kind of configure your crowd. And I invite you to look
in the methods and check those out. But next I wanna talk about using a, what you see
is what you get editor. There's actually a simple one built into easy admin, but
we're gonna go a bit further and install our own, which is going to require some
custom JavaScript. That's next.

