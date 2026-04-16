package com.example.demo;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

@RestController
@RequestMapping("/orders")
public class OrderController {
	@Autowired
	private UserClient userclient;
	@GetMapping("/{oid},{uid}")
	public String getOrder(@PathVariable("oid")String orderId,@PathVariable("uid")String userId) {
		String userInfo=userclient.getUserInfo(userId);
		return "Order#"+orderId+" placed by "+userInfo;
	}
}
