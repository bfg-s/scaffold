<?php

return [
    /**
     * Default properties for generation.
     */
    'defaults' => [
        /**
         * Default properties for generating model data.
         */
        'model' => [
            'path' => 'app/Models',
            'namespace' => 'App\Models',
            'foreign' => 'id',
            'timestamps' => true,
            'type' => 'hasOne',
            'created' => 'created_at',
            'updated' => 'updated_at',
            'property_of_nullables' => true,
            'json_unescaped_unicode' => true,
        ],
        /**
         * Default generation constants.
         * {constant name} => {constant value}.
         */
        'const' => [
            'title' => '{class_name}',
        ],
        /**
         * Model trait data.
         */
        'trait' => [
            'class' => 'App\Models\Traits\{class_name}\{class_name}{name}', //Namespace for the fast trait trade.
            'path' => 'app/Models/Traits/{class_name}/{class_name}{name}.php', //The way to trade a fast trait.
            /**
             * Model personal trait macros.
             */
            'macros' => [
                'Scope' => [
                    'class' => 'App\Models\Traits\{class_name}\{class_name}Scopes',
                    'path' => 'app/Models/Traits/{class_name}/{class_name}Scopes.php',
                ],
                'Getter' => [
                    'class' => 'App\Models\Traits\{class_name}\{class_name}Getters',
                    'path' => 'app/Models/Traits/{class_name}/{class_name}Getters.php',
                ],
                'Setter' => [
                    'class' => 'App\Models\Traits\{class_name}\{class_name}Setters',
                    'path' => 'app/Models/Traits/{class_name}/{class_name}Setters.php',
                ],
            ],
            /**
             * Associations of other traits.
             */
            'associate' => [
                'Authenticatable' => 'Illuminate\Auth\Authenticatable',
                'Authorizable' => 'Illuminate\Foundation\Auth\Access\Authorizable',
                'Notifiable' => 'Illuminate\Notifications\Notifiable',
                'SoftDeletes' => 'Illuminate\Database\Eloquent\SoftDeletes',
                'HasFactory' => 'Illuminate\Database\Eloquent\Factories\HasFactory',
            ],
            /**
             * Default traits for each model.
             */
            'to_each' => [
            /**
             * Now HasFactory added automatically when generating factories is present.
             */
                // 'Illuminate\Database\Eloquent\Factories\HasFactory'
            ],
        ],
        /**
         * Default field positions.
         */
        'field' => [
            'type' => ['string', 191], // Default field positions with no type
            /**
             * Associations of field names with types and their default settings.
             */
            'associate' => [
                'text' => 'text',
                'info' => 'text',
                'price' => ['float', 12, 2],
                'amount' => ['float', 8, 2],
                'email' => ['string', ['nullable']],
                'phone' => ['string', 64, ['nullable']],
                'description' => ['text', ['nullable']],
                'order' => ['integer', ['default' => 0]],
                'active' => ['boolean', ['default' => true, 'order' => 9999]],
            ],
            /**
             * Field name masks with types and their default settings.
             */
            'masks' => [
                'is_*' => ['boolean', ['default' => 1]],
                'price_*' => ['float', 12, 2],
                'amount_*' => ['float', 8, 2],
                '*_price' => ['float', 12, 2],
                '*_amount' => ['float', 8, 2],
                '*_num' => 'integer',
                '*_id' => 'foreignId',
                '*_at' => ['timestamp', ['nullable']],
                '*_text' => ['longText', ['nullable']],
                '*_count' => ['integer', ['default' => 0]],
                '*_description' => ['mediumText', ['nullable']],
            ],
        ],
    ],
    /**
     * Default link names, an attempt to use names not from this list will result in an error.
     */
    'relation_types' => [
        'hasMany' => \Bfg\Scaffold\LevyModel\LevyRelation\LevyHasManyModel::class,
        'hasManyThrough' => \Bfg\Scaffold\LevyModel\LevyRelation\LevyHasManyThroughModel::class,
        'hasOneThrough' => \Bfg\Scaffold\LevyModel\LevyRelation\LevyHasOneThroughModel::class,
        'belongsToMany' => \Bfg\Scaffold\LevyModel\LevyRelation\LevyBelongsToManyModel::class,
        'hasOne' => \Bfg\Scaffold\LevyModel\LevyRelation\LevyHasOneModel::class,
        'belongsTo' => \Bfg\Scaffold\LevyModel\LevyRelation\LevyBelongsToModel::class,
        'morphTo' => \Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphToModel::class,
        'morphOne' => \Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphOneModel::class,
        'morphMany' => \Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphManyModel::class,
        'morphToMany' => \Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphToManyModel::class,
        'morphedByMany' => \Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphedByManyModel::class,
    ],
    'related_name_convert' => [
        'hasMany' => 'plural',
        'hasManyThrough' => 'plural',
        'hasOneThrough' => 'singular',
        'belongsToMany' => 'plural',
        'hasOne' => 'singular',
        'belongsTo' => 'plural',
        'morphTo' => 'plural',
        'morphOne' => 'singular',
        'morphMany' => 'plural',
        'morphToMany' => 'plural',
        'morphedByMany' => 'plural',
    ],
    'relation_reversals_types' => [
        'hasOne' => 'hasMany',
        'hasMany' => 'hasOne',
        'morphOne' => 'morphTo',
        'morphMany' => 'morphTo',
        'morphToMany' => 'morphedByMany',
        'belongsToMany' => 'belongsToMany',
    ],
    'relation_return_collect' => [
        'hasMany', 'hasManyThrough', 'belongsToMany',
        'morphMany', 'morphToMany', 'morphedByMany',
    ],
    'relation_return_model' => [
        'hasOneThrough', 'hasOne', 'belongsTo',
        'morphTo', 'morphOne',
    ],
    'type_associate' => [
        'bigIncrements' => 'integer',
        'bigInteger' => 'integer',
        'binary' => 'string',
        'boolean' => 'boolean',
        'char' => 'string',
        'dateTimeTz' => 'datetime',
        'dateTime' => 'datetime',
        'date' => 'date',
        'decimal' => 'decimal',
        'double' => 'double',
        'enum' => 'string',
        'float' => 'float',
        'foreignId' => 'integer',
        'increments' => 'integer',
        'integer' => 'integer',
        'ipAddress' => 'string',
        'json' => 'json',
        'jsonb' => 'json',
        'lineString' => 'string',
        'longText' => 'string',
        'macAddress' => 'string',
        'mediumIncrements' => 'integer',
        'mediumInteger' => 'integer',
        'mediumText' => 'string',
        'multiLineString' => 'string',
        'set' => 'string',
        'smallIncrements' => 'integer',
        'smallInteger' => 'integer',
        'string' => 'string',
        'text' => 'string',
        'timestampTz' => 'datetime',
        'timestamp' => 'datetime',
        'tinyIncrements' => 'integer',
        'tinyInteger' => 'integer',
        'tinyText' => 'string',
        'unsignedBigInteger' => 'integer',
        'unsignedDecimal' => 'decimal',
        'unsignedInteger' => 'integer',
        'unsignedMediumInteger' => 'integer',
        'unsignedSmallInteger' => 'integer',
        'unsignedTinyInteger' => 'integer',
        'year' => 'integer',
    ],
    /**
     * Add dock blocks PHP.
     */
    'doc_block' => [
        'class' => [
            'model' => true,
            'migrations' => true,
            'factory' => true,
            'cast' => true,
            'seed' => true,
            'observer' => true,
            'request' => true,
            'rule' => true,
            'resource' => true,
            'trait' => true,
        ],
        'props' => [
            'table' => true,
            'timestamps' => true,
            'primaryKey' => true,
            'fillable' => true,
            'casts' => true,
            'attributes' => true,
            'hidden' => true,
            'appends' => true,
            'with' => true,
            'withCount' => true,
            'factory_model' => true,
            'custom' => true,
        ],
        'methods' => [
            'boot' => true,
            'relation' => true,
            'cast_get' => true,
            'cast_set' => true,
            'request_authorize' => true,
            'request_rules' => true,
            'rule_passes' => true,
            'rule_message' => true,
            'migration_up' => true,
            'migration_down' => true,
            'resource_to_array' => true,
            'observer_event' => true,
            'factory_definition' => true,
            'seed_run' => true,
        ],
    ],
    /**
     * Classes for generating model data.
     */
    'parse_pipes' => [
        \Bfg\Scaffold\LevyModel\LevyModel::class => [
            \Bfg\Scaffold\LevyPipes\LevyModel\DetectInformation::class,
            \Bfg\Scaffold\LevyPipes\LevyModel\DetectIdFieldPipe::class,
            \Bfg\Scaffold\LevyPipes\LevyModel\DetectConstantsPipe::class,
            \Bfg\Scaffold\LevyPipes\LevyModel\DetectFieldsPipe::class,
            \Bfg\Scaffold\LevyPipes\LevyModel\DetectDependentTablePipe::class,
            \Bfg\Scaffold\LevyPipes\LevyModel\DetectRelatedTypePipe::class,
            \Bfg\Scaffold\LevyPipes\LevyModel\DetectTraitsPipe::class,
            \Bfg\Scaffold\LevyPipes\LevyModel\DetectTimestampsFieldsPipe::class,
            \Bfg\Scaffold\LevyPipes\LevyModel\DetectObserverPipe::class,
            \Bfg\Scaffold\LevyPipes\LevyModel\DetectRulesPipe::class,
            \Bfg\Scaffold\LevyPipes\LevyModel\DetectResourcesPipe::class,
            \Bfg\Scaffold\LevyPipes\LevyModel\DetectFactoryPipe::class,
            \Bfg\Scaffold\LevyPipes\LevyModel\DetectSeedPipe::class,
            \Bfg\Scaffold\LevyPipes\LevyModel\DetectPropertiesPipe::class,
            \Bfg\Scaffold\LevyPipes\LevyModel\AddTablePipe::class,
        ],
        \Bfg\Scaffold\LevyModel\LevyFieldModel::class => [
            \Bfg\Scaffold\LevyPipes\LevyFieldModel\FieldParsePipe::class,
        ],
        \Bfg\Scaffold\LevyModel\LevyTraitModel::class => [
            \Bfg\Scaffold\LevyPipes\LevyTraitModel\TraitParsePipe::class,
        ],
        \Bfg\Scaffold\LevyModel\LevyConstModel::class => [
            \Bfg\Scaffold\LevyPipes\LevyConstModel\DetectValuePipe::class,
        ],
        \Bfg\Scaffold\LevyModel\LevyRelatedTypeModel::class => [
            \Bfg\Scaffold\LevyPipes\LevyRelatedTypeModel\DetectParamsPipe::class,
        ],
        \Bfg\Scaffold\LevyModel\LevyDependentTableModel::class => [
            \Bfg\Scaffold\LevyPipes\LevyDependentTableModel\DetectInformationPipe::class,
            \Bfg\Scaffold\LevyPipes\LevyModel\DetectFieldsPipe::class,
        ],
        \Bfg\Scaffold\LevyModel\LevyObserverModel::class => [
            \Bfg\Scaffold\LevyPipes\LevyObserverModel\MakeInfoPipe::class,
            \Bfg\Scaffold\LevyPipes\LevyObserverModel\DetectEventsPipe::class,
        ],
        \Bfg\Scaffold\LevyModel\LevyRuleModel::class => [
            \Bfg\Scaffold\LevyPipes\LevyRuleModel\DetectCustomPipe::class,
        ],
        \Bfg\Scaffold\LevyModel\LevyResourceModel::class => [
            \Bfg\Scaffold\LevyPipes\LevyResourceModel\MakeInfoPipe::class,
        ],
        /**
         * Relation pipes.
         */
        \Bfg\Scaffold\LevyModel\LevyRelation\LevyHasManyModel::class => [
            \Bfg\Scaffold\LevyPipes\LevyHasManyModel\ExplainRelationPipe::class,
        ],
        \Bfg\Scaffold\LevyModel\LevyRelation\LevyHasManyThroughModel::class => [
            \Bfg\Scaffold\LevyPipes\LevyHasManyThroughModel\ExplainRelationPipe::class,
        ],
        \Bfg\Scaffold\LevyModel\LevyRelation\LevyHasOneThroughModel::class => [
            \Bfg\Scaffold\LevyPipes\LevyHasOneThroughModel\ExplainRelationPipe::class,
        ],
        \Bfg\Scaffold\LevyModel\LevyRelation\LevyBelongsToManyModel::class => [
            \Bfg\Scaffold\LevyPipes\LevyBelongsToManyModel\ExplainRelationPipe::class, // tables
        ],
        \Bfg\Scaffold\LevyModel\LevyRelation\LevyHasOneModel::class => [
            \Bfg\Scaffold\LevyPipes\LevyHasOneModel\ExplainRelationPipe::class,
        ],
        \Bfg\Scaffold\LevyModel\LevyRelation\LevyBelongsToModel::class => [
            \Bfg\Scaffold\LevyPipes\LevyBelongsToModel\ExplainRelationPipe::class,
        ],
        \Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphOneModel::class => [
            \Bfg\Scaffold\LevyPipes\LevyMorphOneModel\ExplainRelationPipe::class,
        ],
        \Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphToModel::class => [
            \Bfg\Scaffold\LevyPipes\LevyMorphToModel\ExplainRelationPipe::class,
        ],
        \Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphManyModel::class => [
            \Bfg\Scaffold\LevyPipes\LevyMorphManyModel\ExplainRelationPipe::class,
        ],
        \Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphToManyModel::class => [
            \Bfg\Scaffold\LevyPipes\LevyMorphToManyModel\ExplainRelationPipe::class,
        ],
        \Bfg\Scaffold\LevyModel\LevyRelation\LevyMorphedByManyModel::class => [
            \Bfg\Scaffold\LevyPipes\LevyMorphedByManyModel\ExplainRelationPipe::class,
        ],
    ],
    /**
     * Builders from scaffolding listeners.
     */
    'scaffolding_listeners' => [
        \Bfg\Scaffold\Listeners\MakeMigrationListen::class,
        \Bfg\Scaffold\Listeners\MakeTraitListen::class,
        \Bfg\Scaffold\Listeners\MakeModelListen::class,
        \Bfg\Scaffold\Listeners\MakeObserverListen::class,
        \Bfg\Scaffold\Listeners\MakeRequestListen::class,
        \Bfg\Scaffold\Listeners\MakeResourceListen::class,
        \Bfg\Scaffold\Listeners\MakeFactoryListen::class,
        \Bfg\Scaffold\Listeners\MakeSeedingListen::class,
    ],
    /**
     * Cache configs.
     */
    'cache' => [
        'file_list' => app()->bootstrapPath('cache/scaffold_files.php'),
    ],
];
