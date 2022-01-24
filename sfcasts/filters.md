# Filters

Coming soon...

Let's go log out and then log in as our super admin user super admin, at example.com
admin pass, head back to our admin and go back to our user list and perfect as
promised we can now see every user on the system. It would be our user list is pretty
short right now, but it's going to get longer and longer. It would be nice to able to
filter the records in this index section by some criteria, like for example, only
show me users with enabled true or enabled false. Fortunately easy admin has a system
for this called filters over a user crowd controller. I'll go to the bottom and I'm
gonna override yet another method called configure filters.

And this looks and feels a lot like configuring fields. We can call ad and then put
the name aim of a field like enable. And that's it. If we refresh the page, watch
this section around here. We have a new filters button that opens up a model and we
can filter by whatever fields we want. So enable yes or no. So let's say enable no,
and all of these are gone because all of my users are enabled. Now I can go. And
reenable that. And even clear out that filter and notice that enabled in my entity as
a bullion field. And it kind of detected that it knew to make this as a yes or no
check box, just like the field system. There are many different types of filters. And
if you just add it with its via its string, like this easy admin tries to guess the
correct filter to use for you. So just saying ad enabled is the same as saying, add
buoy, filter, call and call new. If you're fresh out and check the filters, that
makes no difference because that was already the field type that it was guessing.

So, as I mentioned, filters are kind of similar to our fields, filters control each
filter class controls how that filter looks in the form up here, and then also how it
modifies the query for the page. So I'm gonna hold command and open my boo filter
class here so we can look at it. You see, it has a new method, just like our fields
and this sets some basic information. The most important thing it sets are the form
type and form type options. The appli method is, is the method that's going to be
called when your filter is applied and it's your job to modify the query. So you can
see up here that this is using something called a boo filter type. I'm actually gonna
hold command and open that. And like all form types. This gives us a bunch of
different options that we can put on it. So apparently there's an expanded option,
which is the reason that we are seeing this field as expanded radio buttons. So let's
try changing that close these two, that file. And at the end of this, I'm going to
say set form type option expanded false. All right. When we try that now, refresh
head of the filters and awesome. Now the non expanded version means it is rendered as
a dropdown.

All right, let's add a couple more filters over to our question section. So I'll go
to my question card controller, Go down to the bottom, override the configure filters
method, just like before, and let's start with one, that's a entity relation. So
every question is a many to one relationship, a one to many relationship, no many to
one relationship, two topic. So let's try adding topic. All right, refresh. We get
our new filter section and topic is this really cool thing where you can just select
whatever topic you want. Now, if you wanted to know what options to pass to that
you'd need to know what the actual filter class is. Usually you can guess a little
bit. So I'm gonna say association filter. No, that's not right.

You would need to know what type of filter that is. And just like with fields, if you
click in the filter class, you can see these eliminate source filter directory in the
bundle. So vendor easy admin bundle source filter, and then you can see a full list
of all the filters in there. So it looks like entity filter is probably the filter
that is being used for the relationship. And you can check out whatever options you
want to see how you configure that. Or you can see how the query logic is done behind
the scenes. All right, let's add a couple more here. I created that Votes And name

And no surprise. The, those all show up. And the coolest thing is just to be able to
see like what they look like. The date field is a really easy way to, uh, check. Even
between dates votes you can do is equal, is greater than is less than. And name is a
different types of fuzzy search that you can apply to that super powerful. We can
also create our own custom filter class. That's as easy as creating a custom class,
making implement that filter interface, use this filter, help trait to help. And then
all you need to do is implement the new method where you set the form type and then
the apply method where you actually apply your changes to the query. Okay. Right now
we have one admin crud controller per entity, but it's totally legal to have multiple
crud controllers for the same one entity. Maybe each shows a different filtered list.
You may not have this use case, but even if that's true, adding, adding a second
admin crud for an entity will help us dive deeper into how easy admin works. That's
next.

