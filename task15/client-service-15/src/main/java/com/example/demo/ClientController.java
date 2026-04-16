package com.example.demo;

import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

@RestController
@RequestMapping("/api")
public class ClientController {

    private final OrderClient orderClient;

    public ClientController(OrderClient orderClient) {
        this.orderClient = orderClient;
    }

    @GetMapping("/{id}")
    public String getFullDetails(@PathVariable String id) {
        return orderClient.getOrder(id);
    }
}