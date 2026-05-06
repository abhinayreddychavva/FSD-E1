package com.example.demo;

import org.springframework.web.bind.annotation.*;

@RestController
public class HelloController {

    @GetMapping("/")
    public String hello() {
        return "CI/CD Pipeline Working!";
    }
}
