<?php

namespace AryehRaber\Captcha\Contracts;

interface CustomShouldVerify
{
    public function __invoke($event): ?bool;
}
