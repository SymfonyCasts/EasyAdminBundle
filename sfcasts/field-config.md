# Field Config

Coming soon...

One other field that we have on user is let me search for it. A roles field, which
actually stores in array of the roles this user should have. And that's probably a
good thing to include in our admins. We can change the roles for a user. The
interesting thing, as I just mentioned about this field is that it stores an array,
but fortunately easy admin has an array field. So check this out. Yield array, field
calling qu new rolls. Let's go check out how that looks. So first, lemme start on the
homepage and nice. It renders it as a com separated list and on the edit page. Oh,
that is really cool. This nice little widget here for adding and removing roles. The
only tricky part might be remembering which roles are available. You actually need to
type them manually, but we can help that out here by going back to our array field
and all of these fields have a method called set help, Where I'll just put a little
message with our available roles. I refresh. Excellent. That's better. But Hmm, now
that I'm seeing this, it strikes me that it might be a little better if we had check
boxes here.

So let's see if we can change the array field to changes, to check boxes. So I'm
gonna hold command and open in array field. And this is really interesting, cuz you
can actually see how the field is configured inside of its new method. It sets the
template name. We're gonna talk about templates later, but it also sets the form
type. So you can see that behind the scenes, the array field uses a collection type.
Now, if you're familiar with these Symfony form component, if you wanna render some
check boxes, you need the choice type. So I'm wondering if we can use the array field
and override the form type to be the choice type. Let's give that a try. So first
before this I'm gonna say rolls equals

And put a list of our roles and then down here after set help, one of the methods you
can call on this is set form type. So you can see set form type and set form type
option. So I'll form type and I'm going to set it through choice type ::class, and
then set form type options. Because one of the options that you have to pass when you
use the form type is choices, and we can set this to array bind, and then pass it
rolls rolls. That's a little bit of a weird thing here. What this is gonna do is
create an array where these are both the keys and the values. So these will be both
the values that are saved to the database. If that field is checked and it will also
be the value that is displayed to the user, and then I'm gonna set multiple to true
cause we can select multiple things and then expand it to true, which is what makes
the choice type render things as, uh, check boxes.

So awesome. And when we try it, it explodes super interesting. So it says an air
occurred, we solving the options of the form choice type, the options allow, add,
allow, delete, delete, empty entry options and entry type do not exist. Hmm. So I
recognize these options here as options that belong to the collection type, which is
the type that the array field is using by default. So this tells me that something
somewhere is actually trying to add these options to our form type, which we don't
want anymore because we're not using the collection type anymore. The really tricky
thing is that you might expect to see in right inside of a Ray field, but it's not
here. So who is setting those options? The answer is something called in a
configurator. So I'm gonna scroll back down to vendor. So I have easy admin, open,
easy Corp, easy admin and bundle source here earlier.

We're looking at something called field and these are all the built in fields. Well,
after all of the fields are after a field is created easy. Admin runs each field
through a configurator system that can make additional changes to those fields. And
this configurator directory holds those. There are a couple of them that are run on
all of them. For example, a common pre configurator. This is a configurator that is,
uh, applied on every single field. You can see it returns true from supports and it
does various kind of normalizations on the field. This common post configure is
another one that configures kind of applies to every single field. But then there are
also a bunch of configurators that are specific to fields, including one called in
array configurator. So you see this array called when the field the is the array
field. So field->get field F QCN is basically checking to say, Hey, is the current
field that's being configured and array field. If it is then call my configure method
so I can make additional changes and here is where those options are being added. So
the configurator system is something we're going to look at and talk about and even
create our own later, but I wanted you to be available, uh, aware of it.

So in this case, we don't really want this configurator to do its work, but
unfortunately we don't really have a, the configurators is always going to, uh, apply
its logic if we're dealing with an array field. And actually that's fine back in user
credit controller. I didn't realize that, but there is actually a choice field which
automatically uses I'll hold command or controlled open it, it automatically uses a
choice type internally. So I don't need to take this array field and try to turn it
into a choice. There's already a built in choice field that is made exactly for this.

And so I don't need to set the form type anymore. I don't need the help and even the
form type options, I probably could set them that way, but the choice field has a
special method called set choices, and then I'll do that same thing, array combined
roles, roles, and, and for the other options, we can say allow multiple choices and
render expand it. How nice is that? All right. So this time we'll go and refresh.
That is what I was hoping for. And back on the homepage, the choice type still
renders as a nice com separated list. By the way, if you wanna see the logic that
makes a choice type render as a com separated list, there is actually a choice
configurator. And if you open that up and kind of SW scroll down a, I think to the
bottom, this says lots of normalization, but down in the, here at the bottom, it says
field->set, formatted value. And that has some logic in here where it, it does the
selective choices and it implodes them with a comma. So the choice configurator is
actually, what's giving us that, oh, and speaking of this type, let me close a couple
of core classes instead of, um, and it, one of the method we can call on here is
render as badges. That affects how that, that, that affects that format of value we
just saw and turns it into these little guys.

All right, next let's handle our user's avatar field, which actually N needs to be an
upload feel.

