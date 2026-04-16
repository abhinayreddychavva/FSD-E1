package com.example.demo.controller;

import java.util.List;

import org.springframework.web.bind.annotation.DeleteMapping;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.PutMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import com.example.demo.model.Product;
import com.example.demo.service.ProductService;

@RestController
@RequestMapping("/products")
public class ProductController {
	public final ProductService service;
	public ProductController(ProductService service) {
		this.service = service;
	}
	@GetMapping("/{id}")
	public Product getProduct(@PathVariable("id")int id){
		return service.findProduct(id);
	}
	@GetMapping("/")
	public List<Product> getProducts(){
		return service.getProducts();
	}
	@PostMapping("/")
	public Product addProduct(@RequestBody Product p) {
		return service.addProduct(p);
	}
	@PutMapping("/{id}")
	public Product updateProduct(@RequestBody Product p,@PathVariable("id")int id) {
		p.setId(id);
		return service.updateProduct(p);
	}
	@DeleteMapping("/{id}")
	public String deleteProduct(@PathVariable("id")int id) {
		service.deleteProduct(id);
		return "Deleted Successfully";
	}
}
