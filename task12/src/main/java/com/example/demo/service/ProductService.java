package com.example.demo.service;

import java.util.List;

import org.springframework.stereotype.Service;

import com.example.demo.model.Product;
import com.example.demo.repository.ProductRepo;

@Service
public class ProductService {
	private ProductRepo repo;

	public ProductService(ProductRepo repo) {
		this.repo = repo;
	}
	public Product addProduct(Product p) {
		return repo.save(p);
	}
	public List<Product> getProducts(){
		return repo.findAll();
	}
	public Product updateProduct(Product p) {
		return repo.save(p);
	}
	public Product findProduct(int id) {
		return repo.findById(id).orElse(null);
	}
	public void deleteProduct(int id) {
		repo.deleteById(id);
	}
}
