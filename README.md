# Scaffold packageThe essence of the package is to design a database and initial data in a visual syntax for further processing it and forming layers for working with laravel.## More detailsA package for creating laravel layers according to a single syntax that is based in a simple array representing a set of rules and conditions that are necessary to build the initial data of the project architecture. We are talking about things like: `Models`, `Model Constants`, `Model Relations`, `Model Properties`, `Model Behaviors (Traits)`, `Casts`, `Migrations`, `Observers`, `Requests`, `Resources`, `Rules`, `Factories`, `Seeders`## InstallationYou can install the library using composer:```bashcomposer require bfg/scaffold```## Beginning of workIf the file `/database/scaffolds.json` does not exist, then after the firstrun of the command` php artisan scaffold` this file will be created automatically!Then you need to fill it with data for generation and re-run the command`php artisan scaffold`.## JSON syntaxFirst, you need to understand how to design an array of scaffolding for your project. > It is important that you definitely need to understand the whole > project in order to make the most correct design of your project > and database in one place.Let's consider a simple design method, this is a `JSON` file.> This approach is simplified for easy integration and fast implementation.Simple example of preschool database structure:```json{  "required": ["user/location"],  "commentary": {    "prop:translatable": ["name"],    "const:title": "Comments",    "type": "morphMany:commentable",    "fields": [      ["name", ["nullable"]],      ["src", ["nullable"]],      "active"    ],    "rules": {      "name": "required|string|min:10|max:191",      "src": "nullable|string|Uppercase"    },    "factory": {      "name": "faker.unique().name()",      "src": "faker.imageUrl(640, 480, 'animals', true)"    },    "seed": "factory:30,50"  },  "rules": {    "const:title": "Rules",    "fields": ["title", "text", "active"],    "relations": {      "commentary": {}    },    "seed": [      {        "title": "Warning 1",        "text": "Warning text 1"      },      {        "title": "Warning 2",        "text": "Warning text 2"      }    ]  },  "director": {    "const:title": "Directors",    "auth": true,    "traits": ["Notifiable", "SoftDeletes", "Getter", "Setter", "Scope"],    "fields": ["name", "last_name", "active"],    "relations": {      "commentary": {},      "reward:hasMany": {        "const:title": "Rewards",        "fields": ["name", "license", "photo", "handed_over_at", "active"]      }    },    "seed": {      "name": "Name",       "last_name": "Last Name"    }  },  "school": {    "const:title": "Schools",    "traits": ["Getter", "Setter", "Scope", "Wets"],    "fields": ["name", "description", "active", "active"],    "relations": {      "director": {},      "commentary": {},      "group:hasMany": {        "const:title": "Groups",        "fields": ["name", ["slogan", ["nullable"]], "active"],        "relations": {          "commentary": {},          "educator:belongsToMany": {            "const:title": "Educators",            "fields": ["name", "last_name", "active"],            "relations": {              "commentary": {}            }          }        }      },      "service:belongsToMany": {        "const:title": "Services",        "fields": ["name", "amount", "description", "active"],        "relations": {          "commentary": {}        }      },      "tags:belongsToMany": {        "const:title": "Tags",        "fields": ["name", "active", "is_new", "mark_at"]      }    }  }}```For publish this demo:```bashphp artisan vendor:publish --tag=scaffold-demo```### Now in detail about the array```{  "{Singular model name}": { //All fields except names are optional    "const:{Constant name}": "Constant value", //optional    "path": "app/Models", //by default    "prop:translatable": ["title"], //For make custom model property    "namespace": "App\\Models", //by default    "foreign": "id", //by default    "observer": [], //observer for model, if the array of values is empty, add all possible events. or list what events are needed in an array.    "timestamps": true, //by default    "traits": ["Getter", "Setter", "Scope", "Wets"], //Injected traits. optional    "type": "{Type name}:{Type parameters}", //The parent type when adding this model as a link. by default hasOne.    "fields": [ //optional      ["{Field name}", "Field param",{"Field methods": "Field properties"}],      ["src", ["nullable"]],      ["bet", "float", 8, 2, {"default": "0.01", "cast": "string"}],      ["href", {"cast": "Href"}], //If Cast is specified with a capital letter, scaffolding will consider it necessary to create a custom class for casting.      "amount" //by default this field is: ['float', 8, 2]    ],    "relations": {      //The name of the relationship can also be plural, in which case it will find or create a model.      "{Relation name}{:Relation type name (optional)}": {        //...new model data, or empty object if model exists      },      "tags:belongsToMany": {        "const:title": "Tags",        "fields": ["name", "active", "is_new", "mark_at"]      },      "rewards": {}    },    "rules": { // For create model request      "name": "required|string|min:10|max:191", // Rules of model request      "src": "nullable|string|Uppercase" // In order to create a custom rule, write the name of the class with a capital letter and it will be created.    },    "resource": [], //To create a resource {model_name}Resource    "resource": ["AdminRules"], //To create multiple resources    "factory": { // For generate of Fabrica      "name": "\faker.unique().name()",      "src": "\faker.imageUrl(640, 480, 'animals', true)",      "time_at": "\now()",      "active": "\rand(0,1)",      "status": "wait",    },    "seed": "factory 30,50", // For add factory to seed you must add this string, for generating 30 to 50 records    "seed": "factory 30", // To generate 30 records    "seed": "factory", // To generate 1 record  }}```## ConfigsFor publish config:```bashphp artisan vendor:publish --tag=scaffold```All properties are documented in a file that were publishedin the `/config/scaffold.php` file.## Clear the generated data from the application.In order to clear constantly regenerated data from your application, run the command `php artisan scaffold:clear`.In order to delete all the files that created the scaffolding altogether, add the `-a` or `--all` flag to the command `php artisan scaffold:clear -a`.## More flexible `php` api### Blanc adding#### Adding named blanc:```php\Bfg\Scaffold\ScaffoldConstruct::namedBlanc("user/location", [    "location" => [        "fields" => [            'lat', 'lon'        ]    ],    "user" => [        "relations" => [            "location" => []        ]    ]]);```#### Adding blanc in to stream:```php\Bfg\Scaffold\ScaffoldConstruct::blanc([    "location" => [        "fields" => [            'lat', 'lon'        ]    ],    "user" => [        "relations" => [            "location" => []        ]    ]]);```### Command adding#### Adding command:```php// Example of default command\Bfg\Scaffold\ScaffoldConstruct::command(    'required',     [\Bfg\Scaffold\ScaffoldCommands::class, 'required']);```