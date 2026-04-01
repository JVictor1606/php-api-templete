<?php

namespace Src\middleware;

interface IMiddleware
{
    public function handle(): void;
}
