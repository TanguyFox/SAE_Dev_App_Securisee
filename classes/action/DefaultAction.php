<?php

namespace netvod\action;

class DefaultAction extends Action
{

    public function execute(): string
    {
        return '
            <div class="list-group">
              <a href="?action=signin" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                  <h5 class="mb-1">Sign in</h5>
                </div>
              </a>
              <a href="?action=register" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                  <h5 class="mb-1">Register</h5>
                </div>
              </a>
            </div>
            ';
    }
}