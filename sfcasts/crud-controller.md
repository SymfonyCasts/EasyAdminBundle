# CRUD Controller

The true reason you use EasyAdmin is for its CRUD controllers. Each CRUD controller will give us a rich set of pages to create, read, update, and delete a single entity. This is where EasyAdmin shines, and the next few minutes are going to be critically important to understanding how EasyAdmin works.

We have four entities, so let's generate a CRUD controller. We'll do that for `Question` first. Find a terminal and run:

```
symfony console make:admin:crud
```

As you can see, it recognizes our four entities. I'll hit 1 for `App/Entity/Question`. Let this generate into the default directory and with default namespace.

Sweet! This did exactly one thing. It created a new `QuestionCrudController` file. Let's go open that up.

Before we look too deeply into this, head over to the admin page and refresh to see... absolutely no difference. We *do* have a new `QuestionCrudController`, but these CRUD controllers are kind of useless until you link to them from a dashboard. So, back over on a `DashboardController`, down at the bottom... `yield MenuItem`, but instead of `linkToDashboard`, there's a number of other things that you can link to. We want to `linkToCrud`. We're gonna pass this a couple things: The label... so `questions`... and an icon, `fa fa-question-circle`. That's a FontAwesome icon. And then, most importantly, the entity's class name. So `Question::class`. Behind the scenes, EasyAdmin's going to recognize that there's only one CRUD dashboard for this entity, which is `QuestionCrudController`, and it will know to use this. And yes, in theory, you can have multiple CRUD controllers per entity. That's something we'll talk about later.

Okay, go refresh to reveal our new link, click and... whoa! This is super cool! We have an `isApproved` slider, which saves automatically. We also have a search bar on top... and sortable columns to help us find whatever we're looking for.

We can edit, delete the edit, and it even has a nice calendar. We are absolutely loaded with awesome features. It's pretty cool! So let's repeat this for our other three controllers.

I'll head back to my terminal and run:

```terminal
Symfony console make:admin:crud
```

Once again, let's get one for `Answer` with the defaults stuff... And one for `Topic` with the default stuff. I'll clear my screen... And the last one we need is for `User`.

Beautiful! The only thing it did is add three more of these CRUD controller classes, but to make those useful, we need to link to them. So, I'm going to paste three more links here. Let's say `Answers`... And I'll customize each of these links.

Nice! I'll refresh... and look! We now have four different admin sections.

All right, I want to look just a little deeper into how this is working behind the scenes. Go to `QuestionCrudController` and look at its base class. Hold "cmd" or "ctrl" and jump into `AbstractCrudController`. We saw earlier that our dashboard extends an `AbstractDashboardController`. Our CRUD controllers extend an `AbstractCrudController`.

Pretty much everything about how our CRUD controller works is going to be controlled by overriding these configure methods that you see inside of here. We'll learn about all of these as we go along. But on a high level, `configureCrud` is here to help you configure the entire CRUD section, `configureAssets` allows you to control custom CSS and JavaScript in this section, and `configureActions` allows you to control the actions you want, which are things like having an index page, having an edit action, or having a delete action. More on that later.

The last important one is `configureFields()`, which is going to configure which fields we see on both the index page and the form. But don't worry about those yet. We'll see all of them later.

Then, below this, you can actually see all of the code that executes the pages. So `index` is our index page, `detail` is a detail show page, `edit` is the edit form.

You can see the code that actually runs behind all of this, which is super useful when we're figuring out how to extend things. But... wait a second. If you scroll back up here to the configure methods, a few of these look familiar. Some of them *also* exist in the dashboard base controller class, specifically `configureAssets`, `configureCrud`, `configureActions`, and `configureFilters`. These all live inside of `AbstractDashboardController`. There's `configureAssets`, and then further down here, CRUD actions and filters.

So what's going on?

Click "Questions" and look at the URL. It starts with `/admin` and then a bunch of query parameters. Turns out, everything EasyAdmin is handled via a single giant route. It all runs through the `DashboardController` route - the `/` route that's above the index. So when we go to the `QuestionCrudController`, it's actually matching this route here with extra query parameters to say which CRUD controller and which action runs. You can see `crudController` and `crudAction` right there. We're rendering `QuestionCrudController`, but in the context of our `DashboardController`.

Anyways, when we go to this page, in order to get the CRUD config, EasyAdmin first calls `configureCrud` on our dashboard. Then it calls `configureCrud` on our specific CRUD controllers. In this case, `QuestionCrudController` is super powerful. It means that we can override things on a dashboard level, like set some CRUD settings to apply to all sections or for one specific CRUD controller.

I'll prove it! Go back to `AbstractDashboardController`. In `configureCrud`, every area has four pages. I'll hold "cmd" and click to open this `Crud` class. There's some constants on top here. Every CRUD section has an index page - that's this - an edit page, a new page, and also a detail page. Each page can then refer to a set of actions. These are links or buttons. For example, on the index page, right now, we have an action for editing and an action for deleting. We *could* add more actions or remove those actions.

You can see how this is configured down in `configureActions`. This is for the dashboard, so this is the action configuration that applies to all of our sections. You can see that for the index page. It adds new "Edit" and "Delete" actions for the detail page. There's `EDIT`, `INDEX`, and `DELETE`. And if you're on the edit page, you have the actions `SAVE_AND_RETURN` and `SAVE_AND_CONTINUE`. One thing you'll notice if you look closely here is that we *do* have a detail page, but nobody actually links to it. You don't see an action calling `DETAIL` on any of these pages. So the page exists, but it's not really used out of the box. So let's add that. 

We'll go to `DashboardController` and it doesn't matter where but I'll go down to the bottom. I'm going to go to "Code Generate" or "cmd + N" on a Mac, go to "Override Methods" and select `configureActions`. Now we want to call the parent method that can create the actions object and set up all of those default actions for us. To do that, add an action to our index page. Use `Crud::index` and then the action we're going to add. We can also use a constant for this: `Action::DETAIL`.

So on the index page, I want to add the action and then the `DETAIL` action. The result of this, when we refresh the `QuestionCreditController`, is that we have this "Show" link that goes to the `DETAIL` action, and we can go to any section. 

And just like that, we've modified every single credit controller in the system! Easy peasy! *But*, since this entity is so simple, let's disable the `DETAIL` action for just this topic section. To do that, open up `TopicCrudController.php`, and just like before, we'll go to "Code Generate" or "cmd + N" on a Mac and go to "Override Methods" and override `configureActions`.

By the time this method is called, it will pass us the `Actions` object that was already set up by our dashboard. So it will already have the `DETAIL` action enabled for the index page. But *now*, we can control that by saying `->disable(Action::DETAIL)`.

We'll talk more about the actions configuration, but these are the main things that you can do inside of them: You can add a new action to a page, *or* you can just completely disable an action globally for all of your pages. As soon as we refresh here, our `DETAIL` action is gone, but if we go to our other sections, it's *still* there.

The big takeaway here is that everything is going through our `DashboardController`, which means that we can configure things on a dashboard level, which will apply to all of our CRUD, *or* we can configure things for one specific CRUD. The fact that all of the CRUD controllers go through this `/admin` URL, where all has one other effect related to security, means that all of our controllers are already secure. That's thanks to our `access_control`.

Remember back in `config/packages/security.yaml`, we had a little access control here that said if the URL starts with `/admin`, require `roll_admin`. So all of these automatically, without doing anything else, require `role_admin`. We'll talk more later about how to secure different admin controllers with different roles, but at the very least, you have to have `role_admin` to get anywhere, which is awesome.

But one important point: Adding this `access_control` was necessary. Why? This action here is what has our route. When we go to a CRUD controller like this, it does match this route, but EasyAdmin does something crazy. Instead of allowing Symfony to call this controller, it sees this `crudController` query parameter here and magically switches the controller to be the real controller - in this case, to be `QuestionCrudController` index. You can actually see this down here on the web debug toolbar. If you hover over "@admin", this tells you that the route name is "admin". So the route name is *actually* this route right here, but the controller is `QuestionCrudController::index`. There's some route magic happening, but ultimately the methods in your CRUD controller are the ones that are called. It's the index method in this abstract CRUD controller down here, which is the actual controller for the page. This is important for security, because if we had only put the route and the route attribute above index and not added the access control, that wouldn't have been enough. This `isGranted` attribute is only enforced when you actually go to your dashboard page.

Anyways, some of that is still a little fuzzy. Don't worry. Stick with me, and we'll keep going through self. That was a blast of EasyAdmin theory to help us understand things a little bit better as we dig further.

Next, before we go deeper into our credit controllers, let's mess around a bit more with our dashboard by adding some custom links to our admin menu and user menu.
