# Post Action

The whole point of this Pending Approval section is to allow moderators to approve or delete questions. We can *delete* questions right now, but there's no way to approve them. Sure, we could add a little "Is Approved" checkbox to the form, but a *true* approved action that we can show on the details page or the index page would be *a lot* nicer. And it would also allow us to run some custom code on approval if we need to. So let's create another custom action.

Over in `QuestionCrudController.php`, we already know how to do this. Say `$approveAction =  Action::new()` and I'll make up the word `approve`. Then, down here at the bottom, let's add that to the detail page: `->add(Crud::PAGE_DETAIL, $approveAction)`.

Before try that, we know we're going to want to do some customizations, so let's give this some CSS classes, `->addCssClass('btn btn-success')`, and an icon, `->setIcon('fa fa-check-circle')`. We also want to say `->displayAsButton()`. By default, an action displays as a link where the URL is wherever you want it to go to. But in this case, we don't want approval to be done with a simple link that makes a "GET" request. Approving something is going to modify data on the server, so we want this to be a "POST" request. This is going to render it as a *button* instead. You'll see how that works in a minute.

Remember, you can't just *create* an action. You need to actually link it to a URL, route, or CRUD action. In this case, we need a CRUD action. On click, that will take the user to new action inside of this controller that we are going to write in a few minutes. So say `linkToCrudAction()`, and the name of the method that we're going to create in a few minutes will be... how about `approve`.

All right, refresh and... the button *isn't* there. The button won't be there on the edit page, but if we go back to the details page... beautiful! There it is - "Approve". But inspect this element and check out the source code. We can see there's literally a button here and that's it. There's no form around this and there's no JavaScript magic to make this submit. In other words, I can click this all day long and absolutely *nothing* is going to happen. We need to wrap this in a form so that we can click this and submit that form to our new URL. How? By leveraging a custom template.

We know that EasyAdmin has *lots* of templates. In EasyAdmin, in `/Resources/views/crud`, there's `action.html.twig`. This is the template that's responsible for rendering every action. You can see it's either an `a` anchor tag or it's a `button` based on our configuration. I'll copy these three lines up here that help hint to the variables we have, and let's go create our own custom template.

Go to `/templates/admin` and add a new file called "approve_action.html.twig". Paste that in... and then, just to further help us know what's going on, let's dump that action variable: `dump(action)`. To use this, over in `QuestionCrudController.php`... right here, I'll say `->setTemplatePath('admin/approve_action.html.twig')`.

Okay, let's try it. Refresh and... cool! We see the dump and we can see all the data on that `ActionDto` object. The most important thing for us is `linkURL`. This points to the URL we need to go to so we can execute our new approve action. And because our new template is only being used by this action, we're free to do whatever we want in here. All of the other actions are still using the core `action.html.twig` template. So what we want to do here is actually make a form: `<form action=""></form>` and then we can use `{{ action.linkUrl }}` and then `method = "POST"`. Inside of this, we want the button. We could create it ourselves, but an easier way to do it is just to `{{ include('@EasyAdmin/crud/action.html.twig') }}`. Perfect!

Reload the page... and inspect that element to see... exactly what we want: the form with the correct action and our button inside. Though, we *do* want to fix the styling there a little bit. I'll just add `class="me-2"`. Refresh and... looks better!

All right, let's try clicking this. We click it and... a *giant error*.

```
The controller for URI "/admin" is not callable: Expected
method "approve" on [our class]
```

It's not there! Our final step is to write that proper action. In `QuestionCrudController.php`, all the way down on the bottom, this is just a *normal* action. You can almost pretend like you're writing this in normal Symfony with a route above it, and we'll just say `public function approve()`. When we get to this URL, inside of here, it's going to contain the ID of our button's entity. We can get that from EasyAdmin by using autowiring `AdminContext, $adminContext`.

Two things about this: `AdminContext` holds everything about EasyAdmin, and it's a service, just like the entity manager or the router. And our `approve()` method is a completely normal Symfony controller. What we're doing here is the normal autowiring of a service that you're used to seeing in all of your normal controllers. So now, we can say `$question = $adminContext->getEntity()->getInstance()`. Sometimes, finding the data you need in `AdminContext` requires a little digging. Then, I'll add a little sanity check here (mostly for my editor): `if (!$question instanceof Question)` and we'll `throw new \LogicException('Entity is missing or not a Question')`. Down here, we can very easily say `$question->setIsApproved(true)`.

And we *already* know how to save this. We need the entity manager. And since this is a normal controller method, we can autowire `EntityManagerInterface $entityManager`, and down here, say `$entityManager->flush()`.

Finally, we need to *do* something here. If you want to, you can render a template. Sometimes you will create actions that are literally a new page in your admin section, and rendering a template here is nothing special. We already did that inside of our `DashboardController.php`. Our `index()` method is a regular action where we rendered a template, so if you wanted to render a template, it would look pretty much exactly like this. But, in this situation, we want to redirect.

You probably know how to redirect from inside of a controller, but in this case, I want to redirect back to the detail page in our admin. In order to generate a URL to somewhere in the admin section, you actually need a special admin URL generator service that can help you get the exact query parameters. Fortunately, it's also available as a service. Say `AdminUrlGenerator $adminUrlGenerator`. Then, we can say `$targetUrl =`, and to start building the URL, we want `$adminUrlGenerator`. Below, add `->setController(self::class)` because we're going to link back to ourself, `->setAction(Crud::PAGE_DETAIL)`, `->setEntityId($question->getId())`, and then *finally*, `->generateUrl()` at the bottom. There are a number of other methods you can call on this builder to set different things, but those are the most important ones. At the bottom, `return $this->redirect($targetUrl)`.

All right, let's give that a try. Refresh and... got it! We're back on the details page. And if we look for "Alice thought she might..." over here, it's not on our Pending Approval page anymore. So that *proves* that it was approved here. Let's look at an easier one here. I'll approve ID 23 - go to Show, click "Approve", and... it's *gone*. This is working!

The only problem now, which you *probably* saw, is that when you go to the detail page on an already approved question, you still see the "Approve" button. Clicking on that won't hurt anything, but it doesn't really make any sense to have it up there. Fortunately, we know how to fix this.

Find your custom action... and we can add `->displayIf()` to that. Pass that a `static function()`, which will receive the `Question $question` argument, and we'll return a `bool`. I've been a little lazy on my return types, but you can put that if you want. And finally, `return (!$question->getIsApproved()`. Move over... refresh and... beautiful! The "Approve" button is *gone*. And if you to go back to a question that *does* need to be approved, it's *still* there.

If we wanted to, we could go further and write some JavaScript for this action. For example, in our custom template, we could use the `stimulus_controller` function to reference some stimulus controller that we write. Then, when we click of this button, we could open a modal that says "Are you sure you want to approve this question?". The point is, *we* control what this action, link, button, etc. looks like. So if you want to attach some custom JavaScript, you can *totally* do that.

Next, let's add a new global action. A "global action" is something that applies to all of the items inside of a section. We are going to create a global *export* action that will export questions to CSV.
