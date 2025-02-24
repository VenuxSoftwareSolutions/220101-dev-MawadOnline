<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it("shows the home page", function () {
    $this->get("/")->assertStatus(200);
});

it("shows the login page", function () {
    $this->get("/users/login")->assertStatus(200);
});

it("shows the user dashboard", function () {
    $this->actingAs($this->user)
       ->get("/dashboard")
       ->assertStatus(200);
});
