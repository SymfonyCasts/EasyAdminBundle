# Form Panels

Coming soon...

Last topic we made it and her admin is getting really customized for this final
trick. I wanna cl closer at the form. Almost all of this is controlled by the field
configuration, each field corresponds to a Symfony form type, then easy admin simply
renders all of those fields. It's basically that simple, easy admin does come with a
custom form theme. And so if you wanted to, for example, make a text type feel, look
different in easy Evan, you could create a custom form theme template. Then this
theme can be added to the crud object and configure crud.

Here we go. For example, we could say->add form theme. And of course you could add
this just to one credit controller, or you could put this quote code into your
dashboard to apply that form theme globally in your admin. But there are a few other
ways that easy admin allows us to control how this page looks, which right now is
just long list of fields over in question crowd controller, up in configure fields.
Here we go down here. Let's see <affirmative> right before the asked by field, I'm
gonna add a special yield form field, so we're starting normal, but instead of saying
new, I'm gonna say, add panel and say details.

Watch what this does gonna refresh. Cool asked by an answers appear under this
details. Headline asked by an answers and also created at, uh, are the only two form
fields of the two fields that appear after our ad panel call. So we get this nice
little panel and then those two fields. And then because all these fields are not
under a panel, they just kind of appear at the bottom, which doesn't really look
great. So when I use an ad panel, I'm gonna put everything in the panel. So right
after my ID field, I don't need to put it, uh, which isn't going to appear in the
form. I'll say form field ad panel, I'll say basic data. So with that setup, Oh, and,
uh, need, make sure I don't forget to yield that. So with that setup, awesome. Basic
data, all the fields below that, then the second panel down here, One property of
these panels, um, these panels have some custom methods on 'em and probably the most
useful is collapsible. So let's make both the, this panel Collapsable and let's make
our other panel Collapsable that you can guess what this does. Yep. We get a nice
little way to collapse both of those sections, which is cool.

And then you can also customize some other things that you might be able to guess at
this point, like set icon FA FA dash info, And also a set help. And I'll say

<affirmative>

Additional details And actually meant to put this down on the other panels. Let me
close that up here. Find my other panel. Here we go. This is where we have our
additional details.

Okay. I,

Now I'm gonna check those down here on our additional details. Cool. A little help.
And I a icon there, by the way, I forgot to mention this, but the changes we're
making affect form page, but they also affect the show page. So if I go and look at a
show page for one of these, you can see that these same organizations are happening
there, which is nice, Or instead of panels, you can use tabs. So I'll change this ad
panel to add tab And then down here, same thing, add tab winery refresh. Now that
shows up as two separate taps. The collapsible doesn't really make sense anymore. I'm
not really sure why that method's even allowed to be called, cuz it doesn't have any
effect. So let's And remove that. And then also you might have noticed that we lost
our icon. So I have this cool little FA FA info icon, but it's not showing up there.
In fact, if you look closely, it is showing up. It's got some space here. If you
inspected element on this, there is an icon there, but it looks a little weird. It's
got a FA FA inside of there.

The fix for this is actually to just change the icon to info. What happens is it's
kind of a bug. It's a little inconsistent, but when you change this to a tab, easy
evidence puts the FA for you. The, uh, So all we need is the info, which will end
showing up right there watching I refresh there, we get F eight info and now it shows
up. And the last thing is, instead of having this long list of fields, you can also
put fields next to each other. You have some control over the columns on this page.
So to show this off, I'm actually going to move my name, field above my slug field.
That won't make too much of a difference. Let's see if we can put these fields next
to each other. So we're using bootstraps. So there's 12 columns on each page.

So on name, I'm gonna say set columns five and then down here on my slug, I'm gonna
do the same thing. So set columns five. I could also say six and use all of the space
now. Very nice to see those float next to each other. Great way to make this page
make a little bit more sense. All right, friends, we are done. This was fun. We
should do it again. Sometime. I love easy admin and we are super happy at Symfony
casts with the admin section that we built with with it, which includes a lot of
custom stuff. Let us know what you're building. And as always, we're here for you
down in the common section with any questions, ideas, or delicious recipes that you
might have. All right. See you next time.

