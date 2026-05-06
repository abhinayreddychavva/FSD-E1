package com.example.demo;

import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/users")
public class UserController {

    private final UserService service;

    public UserController(UserService service) {
        this.service = service;
    }

    @GetMapping("/{id}")
    public String getUser(@PathVariable Long id) {
        return service.getUserName(id);
    }
}
