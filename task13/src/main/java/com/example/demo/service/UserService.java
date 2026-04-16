package com.example.demo.service;

import com.example.demo.entity.User;
import com.example.demo.repository.UserRepository;
import com.example.demo.exception.ResourceNotFoundException;

import org.springframework.stereotype.Service;
import java.util.List;

@Service
public class UserService {

    private final UserRepository repo;

    public UserService(UserRepository repo) {
        this.repo = repo;
    }

    public User create(User user) {
        return repo.save(user);
    }

    public List<User> getAll() {
        return repo.findAll();
    }

    public User getById(Long id) {
        return repo.findById(id)
                .orElseThrow(() -> new ResourceNotFoundException("User not found"));
    }

    public User update(Long id, User updated) {
        User user = getById(id);
        user.setName(updated.getName());
        user.setEmail(updated.getEmail());
        user.setPassword(updated.getPassword());
        return repo.save(user);
    }

    public void delete(Long id) {
        repo.deleteById(id);
    }
}