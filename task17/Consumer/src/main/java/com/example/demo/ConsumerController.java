package com.example.demo;

import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

@RestController
@RequestMapping("/consume")
public class ConsumerController {
	private ConsumerService service;
	
	public ConsumerController(ConsumerService service) {
		super();
		this.service = service;
	}
	@GetMapping("/message")
	public String getMessage(){
		return service.getMessageFromProducer();
	}
	@GetMapping("/error")
	public String getError(){
		return service.getErrorFromProducer();
	}
}
