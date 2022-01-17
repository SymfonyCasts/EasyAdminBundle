# Custom Field

Coming soon...

On the answers crud. We just created this cool little custom votes template, which we
then used by calling the set template path on our votes field. But we also have a
votes field over on questions, which still uses the default. I wanna use this in both
places now, in theory, then technically doing this is really easy. I could copy this
template path. We could go up and find our question. Crowd controller, find our votes
field right here, and then just paste to use that template path. But instead let's
create a custom field. So since a field class like text area, field or association,
field defines how a field looks on the index page and details page as well as how
it's rendered on a form. A custom field is a great way to encompass a bunch of custom
field configuration in one place. So then you can reuse it and creating custom field
is pretty easy. So down in our source, easy admin directory, I'll create a new PHP
class called votes field.

The only rule of I fields that needs to implement field interface. Now this requires
us to have two methods new and get as DTO. Um, but what you'll typically do is use a
field trait. Now I'm gonna click to open that so you can kind of see what it is.
Field trait helps manage the DT, this DTO stuff, and also has a bunch of sort of
common things like set label, set value, set, formatted value that every field
shares. So the only thing that we need to implement, I go down here to code generate
or Command + N on a Mac is the new method. And this is really where we customize all
the options on this field. So to see what we wanna put here, as a reminder, our votes
field is currently an integer field. So I'm gonna hold command or control to open
that integer field and look at its new function. Cause we basically wanted to look
very much like the integer field with a few differences. So let's copy the new field,
all the code from new I'll close this, go to votes, field and paste, and I'll hit
okay to add that use statement up there

And I'm gonna remove this option number format thing. We're not gonna need that. And
it actually relates to a field configurator that I'll show you in a second. Perfect.
Now I notice this set default columns is crossed out because it's marked as internal.
It's actually okay to use this method. Um, it's only meant to be used from inside of
your field, which were inside of a field. So that's okay. Now, in addition to this,
you know, we can customize anything we want. So we could even say add Webpack Encore
entries. So extra, an extra Webpack Encore entry that would be included when this
field is used. Um, you can do whatever you want. Now, what we wanna do is instead of
calling set template and name so that it uses the normal integer field, we're gonna
say set template path and then pass the same thing that we have an answer credit
controller, which is admin /field /votes, HTML twig. So just as a reminder here, this
template is used in the index and detail three pages in the form type.

That's really what controls, how it looks on the edit and new pages. And that's it.
Now we can just go use this. So an answer, correct controller. I'm gonna change this
to a boats field and then we don't need the set temple path. And then in question
controller, I'm gonna do the same thing, boats field, and that's it. If we wanted to,
we could do this text line, right, uh, inside the custom field <affirmative> or
remove it, whatever we want. All right. So let's try it over in question over
refresh. Got it. And of course, over on our answers, it looks great there too. Now,
one thing to be aware of if now that we of changes from an integer field to a votes
field, if there is a specific field configurator for the integers field, it will no
longer be used for our votes field.

And there actually is. If you go back down to vendor, easy Corp, easy admin bundle
source field configurator, there is actually an integer or configurator, and you can
see this operates only when the field you're using is actually integer field. So this
configurator was being used a second ago, but it's not being used now. But if you
look into it, it's just doing some stuff with a custom number format basically allows
the integer field, allows you to control the format that the, uh, number is printed
in. So we don't really need this anyways, cause we're taking, uh, control of how
things are printed, um, inside of our votes, uh, template. So we don't really need
anything custom there. So no big deal, just be aware next let's learn how to
configure a bit more of the crud itself. Like how a crud is sorted by default pation
settings and more okay.

