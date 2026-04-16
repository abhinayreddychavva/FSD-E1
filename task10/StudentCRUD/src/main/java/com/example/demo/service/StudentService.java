package com.example.demo.service;

import java.util.List;

import org.springframework.stereotype.Component;

import com.example.demo.model.student;
import com.example.demo.repository.StudentRepository;

@Component
public class StudentService {
	private final StudentRepository repo;

	public StudentService(StudentRepository repo) {
		this.repo = repo;
	}
	public student saveStudent(student s) {
		return repo.save(s);
		
	}
	public List<student> getAllstudents(){
		return repo.findAll();
		
	}
	public student getstudentbyid(int id) {
		return repo.findById(id).orElse(null);
		
	}
	public student updateStudent(student s) {
		return repo.save(s);
	}
	public void deleteStudent(int id) {
		repo.deleteById(id);
	}

}
