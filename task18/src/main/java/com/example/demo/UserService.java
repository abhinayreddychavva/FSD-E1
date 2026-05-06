package com.example.demo;

import org.springframework.stereotype.Service;

@Service
public class UserService {
    public String getUserName(Long id) {
        if(id == 1) return "John";
        else throw new RuntimeException("User not found");
    }
}
