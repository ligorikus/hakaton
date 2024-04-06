<?php

namespace App\Services\Handler;

class Pathined
{
    public $min_dist = -1;
    public $cur_path = [];
    public $opt_path = [];
    public $destination;

    public function __construct(private array $planets)
    {
    }

    public function find($source, $dst)
    {
        $this->min_dist = 999999999999999;
        $this->destination = $dst;
        $this->recursion($source, 0);
        unset($this->opt_path[0]);
        return $this->opt_path;
    }

    public function recursion($cur, $dist)
    {
        if ($cur === $this->destination) {
            if ($dist < $this->min_dist) {
                $this->min_dist = $dist;
                $this->opt_path = $this->cur_path;
                $this->opt_path[] = $this->destination;
            }
        } else {
            $this->cur_path[] = $cur;
            $this->planets[$cur]['done'] = true;
            foreach ($this->planets[$cur]['paths'] as $p) {
                $next = $p['destination'];
                if (!$this->planets[$next]['done']) {
                    $this->recursion($next, $dist + $p['cost']);
                }
            }
            $this->planets[$cur]['done'] = false;
            array_pop($this->cur_path);
        }
    }
}
