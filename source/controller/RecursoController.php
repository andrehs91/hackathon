<?php

namespace Hackathon\Controller;

use DomainException;

abstract class RecursoController
{
    
    public function get(): void     { throw new DomainException('Método HTTP não implementado.'); }
    public function post(): void    { throw new DomainException('Método HTTP não implementado.'); }
    public function put(): void     { throw new DomainException('Método HTTP não implementado.'); }
    public function patch(): void   { throw new DomainException('Método HTTP não implementado.'); }
    public function delete(): void  { throw new DomainException('Método HTTP não implementado.'); }
    public function head(): void    { throw new DomainException('Método HTTP não implementado.'); }
    public function options(): void { throw new DomainException('Método HTTP não implementado.'); }
    
}
