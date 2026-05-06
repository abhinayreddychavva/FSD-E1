package com.example.demo;

import org.junit.jupiter.api.Test;
import static org.junit.jupiter.api.Assertions.*;

class UserServiceTest {

    UserService service = new UserService();

    @Test
    void testGetUser_success() {
        assertEquals("John", service.getUserName(1L));
    }

    @Test
    void testGetUser_fail() {
        assertThrows(RuntimeException.class, () -> {
            service.getUserName(2L);
        });
    }
}
