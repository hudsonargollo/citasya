/*
 * File name: login_view.dart
 * Last modified: 2024.09.24
 * Author: ClubeMkt - https://clubemkt.online
 * Copyright (c) 2024 CitasYa Bolivia
 */

import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../../../../common/helper.dart';
import '../../../routes/app_routes.dart';
import '../../root/controllers/root_controller.dart';
import '../controllers/auth_controller.dart';

class LoginView extends GetView<AuthController> {
  const LoginView({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    controller.loginFormKey = GlobalKey<FormState>();

    return WillPopScope(
      onWillPop: Helper().onWillPop,
      child: Scaffold(
        backgroundColor: const Color(0xFFFAFDF7),
        body: Stack(
          children: [
            // Background gradient and ambient glow decorations
            Positioned.fill(
              child: Container(
                decoration: const BoxDecoration(
                  gradient: LinearGradient(
                    begin: Alignment.topCenter,
                    end: Alignment.bottomCenter,
                    colors: [
                      Color(0xFFF0FCE6),
                      Color(0xFFFAFDF7),
                      Colors.white,
                    ],
                  ),
                ),
              ),
            ),
            // Top ambient glow
            Positioned(
              top: -80,
              left: -40,
              child: Container(
                width: 260,
                height: 260,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: const Color(0xFF84CC16).withOpacity(0.18),
                ),
              ),
            ),
            // Bottom ambient glow
            Positioned(
              bottom: -60,
              right: -40,
              child: Container(
                width: 240,
                height: 240,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: const Color(0xFF059669).withOpacity(0.12),
                ),
              ),
            ),

            // Main Content Area
            SafeArea(
              child: Column(
                children: [
                  // Top Navigation Bar
                  Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Material(
                          color: Colors.transparent,
                          child: InkWell(
                            onTap: () {
                              try {
                                Get.find<RootController>().changePageOutRoot(0);
                              } catch (_) {
                                Get.back();
                              }
                            },
                            borderRadius: BorderRadius.circular(12),
                            child: Container(
                              padding: const EdgeInsets.all(10),
                              decoration: BoxDecoration(
                                color: Colors.white.withOpacity(0.85),
                                borderRadius: BorderRadius.circular(12),
                                border: Border.all(color: const Color(0xFFE2E8F0)),
                                boxShadow: [
                                  BoxShadow(
                                    color: Colors.black.withOpacity(0.03),
                                    blurRadius: 6,
                                    offset: const Offset(0, 2),
                                  ),
                                ],
                              ),
                              child: const Icon(
                                Icons.arrow_back_ios_new_rounded,
                                size: 18,
                                color: Color(0xFF0F172A),
                              ),
                            ),
                          ),
                        ),
                        // App Brand Pill
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 5),
                          decoration: BoxDecoration(
                            color: const Color(0xFFECFDF5),
                            borderRadius: BorderRadius.circular(20),
                            border: Border.all(color: const Color(0xFFA7F3D0)),
                          ),
                          child: Row(
                            mainAxisSize: MainAxisSize.min,
                            children: const [
                              Icon(
                                Icons.verified_rounded,
                                size: 14,
                                color: Color(0xFF059669),
                              ),
                              SizedBox(width: 4),
                              Text(
                                "CitasYa Clientes",
                                style: TextStyle(
                                  fontSize: 11,
                                  fontWeight: FontWeight.w700,
                                  color: Color(0xFF047857),
                                ),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),

                  // Form Card Container
                  Expanded(
                    child: Center(
                      child: SingleChildScrollView(
                        physics: const BouncingScrollPhysics(),
                        padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
                        child: ConstrainedBox(
                          constraints: const BoxConstraints(maxWidth: 440),
                          child: Form(
                            key: controller.loginFormKey,
                            child: Container(
                              decoration: BoxDecoration(
                                color: Colors.white.withOpacity(0.96),
                                borderRadius: BorderRadius.circular(28),
                                border: Border.all(
                                  color: const Color(0xFFD9F99D).withOpacity(0.9),
                                  width: 1,
                                ),
                                boxShadow: [
                                  BoxShadow(
                                    color: const Color(0xFF0F172A).withOpacity(0.08),
                                    blurRadius: 28,
                                    offset: const Offset(0, 10),
                                  ),
                                  BoxShadow(
                                    color: const Color(0xFF84CC16).withOpacity(0.06),
                                    blurRadius: 10,
                                    offset: const Offset(0, 2),
                                  ),
                                ],
                              ),
                              child: ClipRRect(
                                borderRadius: BorderRadius.circular(28),
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.stretch,
                                  children: [
                                    // Top Card Accent Gradient Bar
                                    Container(
                                      height: 5,
                                      decoration: const BoxDecoration(
                                        gradient: LinearGradient(
                                          colors: [
                                            Color(0xFF84CC16),
                                            Color(0xFF10B981),
                                            Color(0xFF65A30D),
                                          ],
                                        ),
                                      ),
                                    ),

                                    Padding(
                                      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 24),
                                      child: Column(
                                        crossAxisAlignment: CrossAxisAlignment.stretch,
                                        children: [
                                          // Brand Logo
                                          Center(
                                            child: Image.asset(
                                              'assets/img/logocitasya.webp',
                                              height: 46,
                                              fit: BoxFit.contain,
                                              errorBuilder: (_, __, ___) => Image.asset(
                                                'assets/icon/icon.png',
                                                height: 46,
                                              ),
                                            ),
                                          ),
                                          const SizedBox(height: 16),

                                          // Headline & Subtitle
                                          const Text(
                                            "¡Hola de nuevo!",
                                            textAlign: TextAlign.center,
                                            style: TextStyle(
                                              fontSize: 22,
                                              fontWeight: FontWeight.w900,
                                              color: Color(0xFF0F172A),
                                              letterSpacing: -0.5,
                                            ),
                                          ),
                                          const SizedBox(height: 4),
                                          const Text(
                                            "Ingresa tus credenciales para acceder a tus reservas y servicios",
                                            textAlign: TextAlign.center,
                                            style: TextStyle(
                                              fontSize: 12,
                                              fontWeight: FontWeight.w400,
                                              color: Color(0xFF64748B),
                                              height: 1.3,
                                            ),
                                          ),
                                          const SizedBox(height: 24),

                                          // Email Input Field
                                          _buildFieldLabel("CORREO ELECTRÓNICO"),
                                          const SizedBox(height: 6),
                                          _buildEmailInput(context),
                                          const SizedBox(height: 18),

                                          // Password Input Field
                                          Row(
                                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                            children: [
                                              _buildFieldLabel("CONTRASEÑA"),
                                              GestureDetector(
                                                onTap: () => Get.toNamed(Routes.FORGOT_PASSWORD),
                                                child: const Text(
                                                  "¿Olvidaste tu contraseña?",
                                                  style: TextStyle(
                                                    fontSize: 11,
                                                    fontWeight: FontWeight.w600,
                                                    color: Color(0xFF047857),
                                                  ),
                                                ),
                                              ),
                                            ],
                                          ),
                                          const SizedBox(height: 6),
                                          _buildPasswordInput(context),
                                          const SizedBox(height: 24),

                                          // Primary Submit Button
                                          Obx(() => _buildSubmitButton()),
                                          const SizedBox(height: 20),

                                          // Divider
                                          Row(
                                            children: const [
                                              Expanded(child: Divider(color: Color(0xFFF1F5F9), thickness: 1)),
                                              Padding(
                                                padding: EdgeInsets.symmetric(horizontal: 10),
                                                child: Text(
                                                  "O",
                                                  style: TextStyle(
                                                    fontSize: 10,
                                                    fontWeight: FontWeight.w800,
                                                    color: Color(0xFF94A3B8),
                                                    letterSpacing: 1.0,
                                                  ),
                                                ),
                                              ),
                                              Expanded(child: Divider(color: Color(0xFFF1F5F9), thickness: 1)),
                                            ],
                                          ),
                                          const SizedBox(height: 16),

                                          // Signup Prompt
                                          Center(
                                            child: Column(
                                              children: [
                                                const Text(
                                                  "¿Aún no tienes una cuenta?",
                                                  style: TextStyle(
                                                    fontSize: 12,
                                                    fontWeight: FontWeight.w500,
                                                    color: Color(0xFF64748B),
                                                  ),
                                                ),
                                                const SizedBox(height: 8),
                                                Material(
                                                  color: Colors.transparent,
                                                  child: InkWell(
                                                    onTap: () => Get.toNamed(Routes.REGISTER),
                                                    borderRadius: BorderRadius.circular(99),
                                                    child: Container(
                                                      padding: const EdgeInsets.symmetric(
                                                        horizontal: 18,
                                                        vertical: 8,
                                                      ),
                                                      decoration: BoxDecoration(
                                                        color: const Color(0xFFECFDF5),
                                                        borderRadius: BorderRadius.circular(99),
                                                        border: Border.all(
                                                          color: const Color(0xFFA7F3D0),
                                                        ),
                                                      ),
                                                      child: Row(
                                                        mainAxisSize: MainAxisSize.min,
                                                        children: const [
                                                          Icon(
                                                            Icons.person_add_alt_1_rounded,
                                                            size: 15,
                                                            color: Color(0xFF047857),
                                                          ),
                                                          SizedBox(width: 6),
                                                          Text(
                                                            "Registrarme en CitasYa",
                                                            style: TextStyle(
                                                              fontSize: 12,
                                                              fontWeight: FontWeight.w700,
                                                              color: Color(0xFF047857),
                                                            ),
                                                          ),
                                                        ],
                                                      ),
                                                    ),
                                                  ),
                                                ),
                                              ],
                                            ),
                                          ),
                                        ],
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                            ),
                          ),
                        ),
                      ),
                    ),
                  ),

                  // Simple Bottom Copyright
                  Padding(
                    padding: const EdgeInsets.only(bottom: 12.0),
                    child: Text(
                      "© ${DateTime.now().year} CitasYa Bolivia · Todos los derechos reservados.",
                      style: const TextStyle(
                        fontSize: 10,
                        color: Color(0xFF94A3B8),
                        fontWeight: FontWeight.w500,
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildFieldLabel(String label) {
    return Text(
      label,
      style: const TextStyle(
        fontSize: 11,
        fontWeight: FontWeight.w800,
        color: Color(0xFF334155),
        letterSpacing: 0.6,
      ),
    );
  }

  Widget _buildEmailInput(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        color: const Color(0xFFF8FAFC),
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: const Color(0xFFE2E8F0)),
      ),
      child: TextFormField(
        initialValue: controller.currentUser.value.email,
        keyboardType: TextInputType.emailAddress,
        style: const TextStyle(
          fontSize: 13,
          fontWeight: FontWeight.w500,
          color: Color(0xFF0F172A),
        ),
        onSaved: (input) => controller.currentUser.value.email = input?.trim(),
        validator: (input) {
          if (input == null || input.trim().isEmpty) {
            return "El correo electrónico es requerido".tr;
          }
          if (!input.contains('@') || !input.contains('.')) {
            return "Ingresa un correo electrónico válido".tr;
          }
          return null;
        },
        decoration: const InputDecoration(
          hintText: "ejemplo@correo.com",
          hintStyle: TextStyle(
            fontSize: 13,
            color: Color(0xFF94A3B8),
            fontWeight: FontWeight.w400,
          ),
          prefixIcon: Icon(
            Icons.alternate_email_rounded,
            size: 18,
            color: Color(0xFF94A3B8),
          ),
          border: InputBorder.none,
          contentPadding: EdgeInsets.symmetric(horizontal: 14, vertical: 14),
        ),
      ),
    );
  }

  Widget _buildPasswordInput(BuildContext context) {
    return Obx(
      () => Container(
        decoration: BoxDecoration(
          color: const Color(0xFFF8FAFC),
          borderRadius: BorderRadius.circular(14),
          border: Border.all(color: const Color(0xFFE2E8F0)),
        ),
        child: TextFormField(
          initialValue: controller.currentUser.value.password,
          obscureText: controller.hidePassword.value,
          keyboardType: TextInputType.visiblePassword,
          style: const TextStyle(
            fontSize: 13,
            fontWeight: FontWeight.w500,
            color: Color(0xFF0F172A),
          ),
          onSaved: (input) => controller.currentUser.value.password = input,
          validator: (input) {
            if (input == null || input.isEmpty) {
              return "La contraseña es requerida".tr;
            }
            if (input.length < 3) {
              return "Debe tener al menos 3 caracteres".tr;
            }
            return null;
          },
          decoration: InputDecoration(
            hintText: "••••••••••••",
            hintStyle: const TextStyle(
              fontSize: 13,
              color: Color(0xFF94A3B8),
              fontWeight: FontWeight.w400,
            ),
            prefixIcon: const Icon(
              Icons.lock_outline_rounded,
              size: 18,
              color: Color(0xFF94A3B8),
            ),
            suffixIcon: IconButton(
              onPressed: () {
                controller.hidePassword.value = !controller.hidePassword.value;
              },
              icon: Icon(
                controller.hidePassword.value
                    ? Icons.visibility_outlined
                    : Icons.visibility_off_outlined,
                size: 18,
                color: const Color(0xFF64748B),
              ),
            ),
            border: InputBorder.none,
            contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 14),
          ),
        ),
      ),
    );
  }

  Widget _buildSubmitButton() {
    return Material(
      color: Colors.transparent,
      child: InkWell(
        onTap: controller.loading.isTrue ? null : () => controller.login(),
        borderRadius: BorderRadius.circular(99),
        child: Ink(
          height: 48,
          decoration: BoxDecoration(
            color: const Color(0xFF0F172A),
            borderRadius: BorderRadius.circular(99),
            boxShadow: [
              BoxShadow(
                color: const Color(0xFF0F172A).withOpacity(0.25),
                blurRadius: 14,
                offset: const Offset(0, 5),
              ),
            ],
          ),
          child: Center(
            child: controller.loading.isTrue
                ? const SizedBox(
                    width: 20,
                    height: 20,
                    child: CircularProgressIndicator(
                      strokeWidth: 2,
                      valueColor: AlwaysStoppedAnimation<Color>(Color(0xFFA3E635)),
                    ),
                  )
                : Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: const [
                      Text(
                        "Iniciar Sesión",
                        style: TextStyle(
                          fontSize: 14,
                          fontWeight: FontWeight.w800,
                          color: Color(0xFFA3E635),
                          letterSpacing: 0.3,
                        ),
                      ),
                      SizedBox(width: 8),
                      Icon(
                        Icons.arrow_forward_rounded,
                        color: Color(0xFFA3E635),
                        size: 16,
                      ),
                    ],
                  ),
          ),
        ),
      ),
    );
  }
}
