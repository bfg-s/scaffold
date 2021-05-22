<?phpreturn [    /**     * Default properties for generation.     */    'defaults' => [        /**         * Default properties for generating model data.         */        'model' => [            'path' => 'app/Models',            'namespace' => 'App\Models',            'foreign' => 'id',            'timestamps' => true,            'type' => 'hasOne',            'created' => 'created_at',            'updated' => 'updated_at',        ],        /**         * Default generation constants.         * {constant name} => {constant value}         */        'const' => [            'title' => '{class_name}'        ],        /**         * Model trait data         */        'trait' => [            'class' => 'App\Models\Traits\{class_name}\{class_name}{name}Trait', //Namespace for the fast trait trade.            'path' => 'app/Models/Traits/{class_name}/{class_name}{name}Trait.php', //The way to trade a fast trait.            /**             * Model personal trait macros.             */            'macros' => [                'Scope' => [                    'class' => 'App\Models\Traits\{class_name}\{class_name}ScopesTrait',                    'path' => 'app/Models/Traits/{class_name}/{class_name}ScopesTrait.php',                ],                'Getter' => [                    'class' => 'App\Models\Traits\{class_name}\{class_name}GettersTrait',                    'path' => 'app/Models/Traits/{class_name}/{class_name}GettersTrait.php',                ],                'Setter' => [                    'class' => 'App\Models\Traits\{class_name}\{class_name}SettersTrait',                    'path' => 'app/Models/Traits/{class_name}/{class_name}SettersTrait.php',                ],            ],            /**             * Associations of other traits             */            'associate' => [                'Authenticatable' => 'Illuminate\Auth\Authenticatable',                'Authorizable' => 'Illuminate\Foundation\Auth\Access\Authorizable',                'Notifiable' => 'Illuminate\Notifications\Notifiable',                'SoftDeletes' => 'Illuminate\Database\Eloquent\SoftDeletes',            ],            /**             * Default traits for each model             */            'to_each' => [                'Illuminate\Database\Eloquent\Factories\HasFactory'            ]        ],        /**         * Default field positions         */        'field' => [            'type' => ['string', 191], // Default field positions with no type            /**             * Associations of field names with types and their default settings.             */            'associate' => [                'text' => 'text',                'info' => 'text',                'active' => ['boolean', ['default' => 1]],                'price' => ['float', 12, 2],                'amount' => ['float', 8, 2],                'email' => ['string', ['nullable']],                'phone' => ['string', 64, ['nullable']],                'description' => ['text', ['nullable']],            ],            /**             * Field name masks with types and their default settings.             */            "masks" => [                'is_*' => ['boolean', ['default' => 1]],                '*_num' => 'int',                '*_id' => 'foreignId',                '*_at' => ['timestamp', ['nullable']],                '*_text' => ['longText', ['nullable']],                '*_count' => ['int', ['default' => 0]],                '*_description' => ['mediumText', ['nullable']],            ]        ],    ],    /**     * Default link names, an attempt to use names not from this list will result in an error.     */    'relation_types' => [        'hasMany' => \Bfg\Scaffold\LevyModel\LevyRelation\LevyHasManyModel::class,        'hasManyThrough' => \Bfg\Scaffold\LevyModel\LevyRelation\LevyHasManyThroughModel::class,        'hasOneThrough' => \Bfg\Scaffold\LevyModel\LevyRelation\LevyHasOneThroughModel::class,        'belongsToMany' => \Bfg\Scaffold\LevyModel\LevyRelation\LevyBelongsToManyModel::class,        'hasOne' => \Bfg\Scaffold\LevyModel\LevyRelation\LevyHasOneModel::class,        'belongsTo' => \Bfg\Scaffold\LevyModel\LevyRelation\LevyBelongsToModel::class,        'morphTo' => \Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphToModel::class,        'morphOne' => \Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphOneModel::class,        'morphMany' => \Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphManyModel::class,        'morphToMany' => \Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphToManyModel::class,        'morphedByMany' => \Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphedByManyModel::class,    ],    'relation_reversals_types' => [        'hasOne' => 'belongsTo',        'hasMany' => 'belongsTo',        'morphOne' => 'morphTo',        'morphMany' => 'morphTo',        'morphToMany' => 'morphedByMany',        'belongsToMany' => 'belongsToMany',    ],    'relation_return_collect' => [        'hasMany', 'hasManyThrough', 'belongsToMany',        'morphMany', 'morphToMany', 'morphedByMany'    ],    'relation_return_model' => [        'hasOneThrough', 'hasOne', 'belongsTo',        'morphTo', 'morphOne'    ],    'type_associate' => [        'bigIncrements' => 'integer',        'bigInteger' => 'integer',        'binary' => 'string',        'boolean' => 'boolean',        'char' => 'string',        'dateTimeTz' => 'datetime',        'dateTime' => 'datetime',        'date' => 'date',        'decimal' => 'decimal',        'double' => 'double',        'enum' => 'string',        'float' => 'float',        'foreignId' => 'integer',        'increments' => 'integer',        'integer' => 'integer',        'ipAddress' => 'string',        'json' => 'json',        'jsonb' => 'json',        'lineString' => 'string',        'longText' => 'string',        'macAddress' => 'string',        'mediumIncrements' => 'integer',        'mediumInteger' => 'integer',        'mediumText' => 'string',        'multiLineString' => 'string',        'set' => 'string',        'smallIncrements' => 'integer',        'smallInteger' => 'integer',        'string' => 'string',        'text' => 'string',        'timestampTz' => 'datetime',        'timestamp' => 'datetime',        'tinyIncrements' => 'integer',        'tinyInteger' => 'integer',        'tinyText' => 'string',        'unsignedBigInteger' => 'integer',        'unsignedDecimal' => 'decimal',        'unsignedInteger' => 'integer',        'unsignedMediumInteger' => 'integer',        'unsignedSmallInteger' => 'integer',        'unsignedTinyInteger' => 'integer',        'year' => 'integer',    ],    /**     * Classes for generating model data.     */    'parse_pipes' => [        \Bfg\Scaffold\LevyModel\LevyModel::class => [            \Bfg\Scaffold\LevyPipes\LevyModel\DetectInformation::class,            \Bfg\Scaffold\LevyPipes\LevyModel\DetectIdFieldPipe::class,            \Bfg\Scaffold\LevyPipes\LevyModel\DetectConstantsPipe::class,            \Bfg\Scaffold\LevyPipes\LevyModel\DetectFieldsPipe::class,            \Bfg\Scaffold\LevyPipes\LevyModel\DetectDependentTablePipe::class,            \Bfg\Scaffold\LevyPipes\LevyModel\DetectRelatedTypePipe::class,            \Bfg\Scaffold\LevyPipes\LevyModel\DetectRelationsPipe::class,            \Bfg\Scaffold\LevyPipes\LevyModel\DetectTraitsPipe::class,            \Bfg\Scaffold\LevyPipes\LevyModel\DetectTimestampsFieldsPipe::class,            \Bfg\Scaffold\LevyPipes\LevyModel\DetectObserverPipe::class,            \Bfg\Scaffold\LevyPipes\LevyModel\AddTablePipe::class,        ],        \Bfg\Scaffold\LevyModel\LevyFieldModel::class => [            \Bfg\Scaffold\LevyPipes\LevyFieldModel\FieldParsePipe::class,        ],        \Bfg\Scaffold\LevyModel\LevyTraitModel::class => [            \Bfg\Scaffold\LevyPipes\LevyTraitModel\TraitParsePipe::class,        ],        \Bfg\Scaffold\LevyModel\LevyConstModel::class => [            \Bfg\Scaffold\LevyPipes\LevyConstModel\DetectValuePipe::class,        ],        \Bfg\Scaffold\LevyModel\LevyRelatedTypeModel::class => [            \Bfg\Scaffold\LevyPipes\LevyRelatedTypeModel\DetectParamsPipe::class,        ],        \Bfg\Scaffold\LevyModel\LevyDependentTableModel::class => [            \Bfg\Scaffold\LevyPipes\LevyDependentTableModel\DetectInformationPipe::class,            \Bfg\Scaffold\LevyPipes\LevyModel\DetectFieldsPipe::class,        ],        \Bfg\Scaffold\LevyModel\LevyObserverModel::class => [            \Bfg\Scaffold\LevyPipes\LevyObserverModel\MakeInfoPipe::class,            \Bfg\Scaffold\LevyPipes\LevyObserverModel\DetectEventsPipe::class,        ],        /**         * Relation pipes         */        \Bfg\Scaffold\LevyModel\LevyRelation\LevyHasManyModel::class => [            \Bfg\Scaffold\LevyPipes\LevyHasManyModel\ExplainRelationPipe::class,        ],        \Bfg\Scaffold\LevyModel\LevyRelation\LevyHasManyThroughModel::class => [            \Bfg\Scaffold\LevyPipes\LevyHasManyThroughModel\ExplainRelationPipe::class,        ],        \Bfg\Scaffold\LevyModel\LevyRelation\LevyHasOneThroughModel::class => [            \Bfg\Scaffold\LevyPipes\LevyHasOneThroughModel\ExplainRelationPipe::class,        ],        \Bfg\Scaffold\LevyModel\LevyRelation\LevyBelongsToManyModel::class => [            \Bfg\Scaffold\LevyPipes\LevyBelongsToManyModel\ExplainRelationPipe::class, // tables        ],        \Bfg\Scaffold\LevyModel\LevyRelation\LevyHasOneModel::class => [            \Bfg\Scaffold\LevyPipes\LevyHasOneModel\ExplainRelationPipe::class,        ],        \Bfg\Scaffold\LevyModel\LevyRelation\LevyBelongsToModel::class => [            \Bfg\Scaffold\LevyPipes\LevyBelongsToModel\ExplainRelationPipe::class,        ],        \Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphOneModel::class => [            \Bfg\Scaffold\LevyPipes\LevyMorphOneModel\ExplainRelationPipe::class,        ],        \Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphToModel::class => [            \Bfg\Scaffold\LevyPipes\LevyMorphToModel\ExplainRelationPipe::class,        ],        \Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphManyModel::class => [            \Bfg\Scaffold\LevyPipes\LevyMorphManyModel\ExplainRelationPipe::class,        ],        \Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphToManyModel::class => [            \Bfg\Scaffold\LevyPipes\LevyMorphToManyModel\ExplainRelationPipe::class,        ],        \Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphedByManyModel::class => [            \Bfg\Scaffold\LevyPipes\LevyMorphedByManyModel\ExplainRelationPipe::class,        ]    ],    /**     * Builders from scaffolding listeners     */    'scaffolding_listeners' => [        \Bfg\Scaffold\Listeners\MakeMigrationListen::class,        \Bfg\Scaffold\Listeners\MakeTraitListen::class,        \Bfg\Scaffold\Listeners\MakeModelListen::class,        \Bfg\Scaffold\Listeners\MakeObserverListen::class,//        \Bfg\Scaffold\Listeners\MakeCommandListen::class,//        \Bfg\Scaffold\Listeners\MakeControllerListen::class,//        \Bfg\Scaffold\Listeners\MakeEventListen::class,//        \Bfg\Scaffold\Listeners\MakeFactoryListen::class,//        \Bfg\Scaffold\Listeners\MakeJobListen::class,//        \Bfg\Scaffold\Listeners\MakeLanguageListen::class,//        \Bfg\Scaffold\Listeners\MakeMailListen::class,//        \Bfg\Scaffold\Listeners\MakeMiddlewareListen::class,//        \Bfg\Scaffold\Listeners\MakeRequestListen::class,//        \Bfg\Scaffold\Listeners\MakeResourceListen::class,//        \Bfg\Scaffold\Listeners\MakeSeedingListen::class,    ],    /**     * Cache configs     */    'cache' => [        'file_list' => app()->bootstrapPath('cache/scaffold_files.php')    ]];