# laravel
## other php frameworks
- Symfony
- CodeIgniter
## what is the relation between laravel & Ruby on Rails
- laravel was inspired by the principles and concepts of Ruby on Rails
## who made laravel is Taylor
## what was done in every version of laravle
1. laravel 1:
    - module system for extension 
    - helper for forms
    - validation
    - authentication
2. laravel 2 & 3:
    - controllers
    - unit testing
    - command lines
    - eleqouent relationships
    - migrations
3. laravel 4:
    - developed a set of components under the code name Illuminate
    - queues
    - mail component
    - facades
    - database seeding 
4. laravel 5:
    ### Revamped Directory Structure:

Laravel 5 introduced a more organized and intuitive directory structure compared to its previous versions. The directory structure was restructured to provide better separation of concerns and make it easier for developers to locate and manage their application files. Some of the key directories included in the revamped structure are:
1. app: Contains application-specific code, including controllers, models, and service providers.
2. config: Holds configuration files for various parts of the application.
3. resources: Contains assets like views, language files, and assets such as CSS and JavaScript.
4. public: Houses the publicly accessible files, including the front controller (index.php) and assets.
5. database: Stores database-related files, including migrations and seeders.

    ### Removal of Form and HTML Helpers:

- Laravel 5 removed the built-in Form and HTML helpers that were present in earlier versions. These helpers were used to generate HTML forms and various HTML elements, making form generation more convenient. However, their removal was part of a shift towards encouraging the use of Blade templates and modern front-end technologies like JavaScript frameworks (e.g., Vue.js or React) for handling user interfaces.
- Developers were encouraged to create forms and HTML elements using Blade templates, which provided more flexibility and control over the generated HTML.
  
    ### HTML helpers

    ### spate of new views

    ### socialite for social media authentication

    ### form requests

    ### REPL (Read–Evaluate–Print Loop):

- A REPL is an interactive programming environment where you can enter code and immediately see the results. Laravel 5 introduced a REPL tool called tinker that allowed developers to interactively test and experiment with Laravel code and components from the command line.

- With tinker, you could execute Laravel code and access your application's classes and data interactively, making it useful for debugging, exploring your application's data, and testing various components.
- This interactive REPL greatly simplified the process of debugging and exploring your Laravel application in real-time.
## some laravel packages
1. Cashier:payments and sub‐scriptions
2. Echo: WebSockets
3. Scout: search
4. Passport: API authentication
5. Dusk: frontend testing
6. Socialite: social login
7. Horizon: monitoring queues,
8. Nova: building admin panels
9. Spark: bootstrap your SaaS
## what are Illuminate and Spark
- `Illuminate` is the foundational component of Laravel that provides a wide range of classes and components used throughout the framework. It is essentially the core of Laravel and includes many sub-components, such as the Eloquent ORM, the Blade templating engine, the Routing system, the Validation system, and more.
- `Spark` is not a core component of Laravel but rather an additional offering. It is a commercial package provided by Laravel that simplifies the process of building SaaS (Software as a Service) applications.