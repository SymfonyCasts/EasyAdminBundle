# Custom Stimulus JavaScript Controller

We just created a Stimulus controller. *Now* we need to *apply* this controller to
the "row" that's around each field. Let me make things a bit smaller. So we're
going to apply the controller to this row. The code in the controller will *watch*
the textarea for changes and render a preview.

The whole flow looks like this. When that row first appears on the page, the
`initialize()` method will add a preview div. Then, whenever we type into the field,
Stimulus will call `render()`... which will render the HTML preview. We're not going
to talk more about the Stimulus code, but if you have any questions, let us know
in the comments.

Thanks to the fact that `admin.js` is importing `bootstrap.js`, which initializes
all of the controllers in the `controllers/` directory, our new `snarkdown_controller`
*is* already available in the admin section. So, we can get to work!

On the field, call `setFormTypeOptions()` and pass this an array. We need to set
a few attributes. The first is `row_attr`: the attributes that you want to add
to the form "row". This is not an Easy Admin thing... it's a normal option
inside Symfony's form system. Add a `data-controller` attribute set to `snarkdown`.
I *did* just typo that, which is going to *totally* confuse future me.

Next pass an `attr` option: the attributes that should be added the textarea itself.
Add one called `data-snarkdown-target` set to `input`. In Stimulus language, this
makes the textarea a "target"... so that it's easy for us to find. Also add
`data-action` set to `snarkdown#render`.

This says: whenever the textarea changes, call the `render()` method on our `snarkdown`
controller.

Let's try this! Move over and refresh... and type a little... hmm. No preview.
And no errors in the console either. Debugging time! Inspect the element.
Bah! A typo on the controller name... so the controller was never initialized.

Fix that - `snarkdown` - and now when we refresh, there it is! It starts with a
preview... and when we type... it instantly updates to show that as bold. Awesome!

Though, we could style this a bit better... and fortunately we know how to add
CSS to our admin area. In `admin.css`, add a `.markdown-preview` selector. This
is the class that the preview div has when we add it. Let's give this some margin,
a border and some padding.

And now... neato! And to make this *even* cooler, in `QuestionCrudController`,
on the field, call `->setHelp('Preview')`.

Help messages render below the field... so... ah. This gives the preview a
little header.

## Making Admin Controllers Lazy

So with the combination of Stimulus and an `admin.js` file that imports
`bootstrap.js`, we can add custom JavaScript to our admin section simply
by dropping a new controller into the `controllers/` directory.

This *does* create one small problem. *Every* file in the `controllers/` directory
is *also* registered and packaged into the built `app.js` file for the frontend.
This means that users that visit our frontend are downloading `snarkdown_controller`
*and* snarkdown itself. That's probably not a security problem... but it *is* wasteful
and will slow down the frontend experience.

My favorite way to fix this is to go into the controller and add a superpower
that's special to Stimulus inside of Symfony. Put a comment directly above the
controller with `stimulusFetch` colon then inside single quotes `lazy`.

What does that do? It tells Encore to *not* download this controller code - *or*
anything it imports - until the moment that an element appears on the page that
matches this controller. In other words, the code *won't* be downloaded immediately.
But then, the *moment* a `data-controller="snarkdown"` element appears on the page,
it'll be downloaded via Ajax and executed. Pretty perfect for admin stuff.

Check it out. On your browser, go back to the admin section. Pull up your
network tools and go to the Questions section. I'll make the tools bigger... then
go edit a question. On the network tools filter, click "JS".

Check out this last entry: `assets_controllers_snarkdown_controller_js.js`. *That*
is the file that contains our `snarkdown_controller` code. And notice the "initiator"
is "load_script". That's a Webpack function that tells me that this was downloaded
*after* the page was loaded. Specifically, once the textarea appeared on the
page.

And if we visit any *different* page... yep! That file was *not* downloaded at all
because there is *no* `data-controller="snarkdown"` element on the page.

Next, it's finally time to do something with our dashboard! Let's render a chart
and talk about what other things you can do with your admin section's landing page.
