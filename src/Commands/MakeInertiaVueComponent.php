<?php

namespace Pkboom\InertiaVueComponent\Commands;

use Error;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use PhpParser\Node;
use PhpParser\NodeFinder;
use PhpParser\ParserFactory;

class MakeInertiaVueComponent extends Command
{
    protected $signature = 'make:inertia-vue-component {controller}';

    public function handle()
    {
        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);

        try {
            $stmts = $parser->parse(file_get_contents($this->path($this->argument('controller'))));
        } catch (Error $error) {
            $this->error($error->getMessage());

            return 1;
        }

        File::ensureDirectoryExists(resource_path('js/Pages'));

        $nodes = (new NodeFinder())->find($stmts, function (Node $node) {
            return $node instanceof Node\Stmt\Return_;
        });

        collect($nodes)->filter(function ($node) {
            return $node->expr->class->parts[0] === 'Inertia' && $node->expr->name->name === 'render';
        })->map(function ($node) {
            return (object) [
                'name' => $node->expr->args[0]->value->value,
                'props' => collect($node->expr->args[1]->value->items)->map(function ($value) {
                    return $value->key->value;
                }),
            ];
        })->filter(function ($component) {
            return !is_file(resource_path("js/Pages/{$component->name}.vue"));
        })->each(function ($component) {
            $directories = explode('/', $component->name);

            $name = array_pop($directories);

            File::ensureDirectoryExists(resource_path('js/Pages/'.implode('/', $directories)));

            $props = collect($component->props)->map(function ($prop) use ($name) {
                $type = $this->choice("`$name.vue` > `$prop` type?", ['Array', 'Object', 'String', 'Number', 'Boolean'], 0);

                return "\t\t$prop: $type,";
            })->implode(PHP_EOL);

            file_put_contents(resource_path("js/Pages/{$component->name}.vue"), $this->component($props));
        })->each(function ($component) {
            $this->info(resource_path("js/Pages/{$component->name}.vue"));
        });

        return 0;
    }

    public function path($controller)
    {
        return app_path("Http/Controllers/$controller.php");
    }

    public function component($props)
    {
        return <<<Vue
<template>
  <div>
    
  </div>
</template>

<script>
export default {
  props: {
$props
  }, 
  data() {
    return {}
  },
  mounted() {},
  methods: {},
}
</script>
Vue;
    }
}
