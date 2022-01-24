# Sub Menu

Coming soon...

<affirmative>

Our menu on the left here is getting a little bit long and it's especially confusing.
Cause we actually have two question links here. So what we can do is divide this into
a sub menu. So we do that instead of dashboard controller because that's of course
where we configure our menu it. So to create a sub menu, it starts the same way you
say, yield menu item, and then call, call sub menu, and then give that sub menu a
name like questions, And then an icon like normal FA FA Circle to populate the sub
menus on there. You're gonna say set, set, sub items, pass this array, and then I'm
gonna wrap my other two menu item objects inside of there. Of course, when I do that,
I need to indent remove the yield and then I'll replace the semicolons with com.
Perfect. Now we can retrain change questions to how about all, And we can also play
with the icon. So I'm gonna change this to FA list And down here. How about FA FA
dash warning? Let's try that move over. Refresh and Ugh, so much cleaner. In
addition, we can also do separators cuz we just have sort of our dashboard, our
entity links and then homepage. So we can add kind of some separators to separate
these, these sections. And we do that with something called a section. So right after
linked dashboard, you're basically gonna add these sections where you want a
horizontal line to appear. So yield menu item, section,

And I'll say content and let's you put one more down here.

You'll menu item on calling section and for the label this time, I'm just going to
leave it blank. <affirmative> so in this case, unlike the, the menu items, which kind
of wrap the sub menu, which wraps these menu items, these sections, you just kind of
put them wherever you want a separate her I'm gonna refresh. Awesome. So you can see,
we get a little separated here. It says content down here just gives us a nice
separator without any text. And we saw earlier that you can make links on this, go
anywhere. So going to other crud, admin crud is the most common, but you can also
just link to anywhere out like our homepage. So of course not surprisingly, you can
also link to external sites. Like let's say that We love stack overflow so much Wanna
link to it. I'll tweak the icons and then for the URL,

Let me just put whatever URL we want there. Oh, let me fix my on name. So that works,
but that's nothing new. But one thing that we can do that I want to mention is that
these menu items have a lot of options on them. So we know we have things like set
permission and set controller. We also have things like set link, talk, link, target,
or link link re or a set CSS class or a set query parameter. So make sure you take
advantage of the methods that you have on your menu items. So you can customize this.
So in this case, I'm gonna set the link target To_blank. And now if I click stack
overflow, I get it popped up in a new tab. All right. And with just those quick
little tips there, we got a much nicer looking menu. All right. Next, what if we need
to disable an action on an, an entity by entity basis? Like for example, what if we
only want to allow a question to be deleted? If it is not approved, let's dive into
that.

