/*
 * File name: phone_verification_view.dart
 * Last modified: 2024.09.24
 * Author: ClubeMkt - https://clubemkt.online
 * Copyright (c) 2024 CitasYa Bolivia
 */

import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../../../../common/helper.dart';
import '../controllers/auth_controller.dart';

class PhoneVerificationView extends GetView<AuthController> {
  const PhoneVerificationView({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
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
                            onTap: () => Get.back(),
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
                        // Badge Pill
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 5),
                          decoration: BoxDecoration(
                            color: const Color(0xFF0F172A),
                            borderRadius: BorderRadius.circular(20),
                          ),
                          child: Row(
                            mainAxisSize: MainAxisSize.min,
                            children: const [
                              Icon(
                                Icons.phonelink_ring_rounded,
                                size: 14,
                                color: Color(0xFFA3E635),
                              ),
                              SizedBox(width: 5),
                              Text(
                                "Verificación SMS",
                                style: TextStyle(
                                  fontSize: 11,
                                  fontWeight: FontWeight.w800,
                                  color: Color(0xFFA3E635),
                                  letterSpacing: 0.3,
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
                                            height: 44,
                                            fit: BoxFit.contain,
                                            errorBuilder: (_, __, ___) => Image.asset(
                                              'assets/icon/icon.png',
                                              height: 44,
                                            ),
                                          ),
                                        ),
                                        const SizedBox(height: 14),

                                        // Headline & Subtitle
                                        const Text(
                                          "Verifica tu Teléfono",
                                          textAlign: TextAlign.center,
                                          style: TextStyle(
                                            fontSize: 22,
                                            fontWeight: FontWeight.w900,
                                            color: Color(0xFF0F172A),
                                            letterSpacing: -0.5,
                                          ),
                                        ),
                                        const SizedBox(height: 6),
                                        const Text(
                                          "Enviamos un código de seguridad por SMS a tu número. Ingrésalo a continuación para continuar",
                                          textAlign: TextAlign.center,
                                          style: TextStyle(
                                            fontSize: 12,
                                            fontWeight: FontWeight.w400,
                                            color: Color(0xFF64748B),
                                            height: 1.35,
                                          ),
                                        ),
                                        const SizedBox(height: 24),

                                        // OTP Input Field
                                        _buildFieldLabel("CÓDIGO DE VERIFICACIÓN (OTP)"),
                                        const SizedBox(height: 6),
                                        _buildOtpInput(),
                                        const SizedBox(height: 24),

                                        // Primary Submit Button
                                        Obx(() => _buildSubmitButton()),
                                        const SizedBox(height: 18),

                                        // Resend Code Link
                                        Center(
                                          child: TextButton.icon(
                                            onPressed: () => controller.resendOTPCode(),
                                            icon: const Icon(
                                              Icons.replay_rounded,
                                              size: 16,
                                              color: Color(0xFF047857),
                                            ),
                                            label: const Text(
                                              "Reenviar código OTP",
                                              style: TextStyle(
                                                fontSize: 12,
                                                fontWeight: FontWeight.w700,
                                                color: Color(0xFF047857),
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
                          ),
                        ),
                      ),
                    ),
                  ),

                  // Simple Bottom Copyright
                  Padding(
                    padding: const EdgeInsets.only(bottom: 12.0),
                    child: Text(
                      "© ${DateTime.now().year} CitasYa Business · Todos los derechos reservados.",
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

  Widget _buildOtpInput() {
    return Container(
      decoration: BoxDecoration(
        color: const Color(0xFFF8FAFC),
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: const Color(0xFFE2E8F0)),
      ),
      child: TextFormField(
        keyboardType: TextInputType.number,
        textAlign: TextAlign.center,
        style: const TextStyle(
          fontSize: 22,
          fontWeight: FontWeight.w800,
          color: Color(0xFF0F172A),
          letterSpacing: 10,
        ),
        onChanged: (input) => controller.smsSent.value = input.trim(),
        decoration: const InputDecoration(
          hintText: "• • • • • •",
          hintStyle: TextStyle(
            fontSize: 20,
            color: Color(0xFF94A3B8),
            fontWeight: FontWeight.w400,
            letterSpacing: 8,
          ),
          border: InputBorder.none,
          contentPadding: EdgeInsets.symmetric(horizontal: 14, vertical: 14),
        ),
      ),
    );
  }

  Widget _buildSubmitButton() {
    return Material(
      color: Colors.transparent,
      child: InkWell(
        onTap: controller.loading.isTrue ? null : () => controller.verifyPhone(),
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
                        "Verificar y Continuar",
                        style: TextStyle(
                          fontSize: 14,
                          fontWeight: FontWeight.w800,
                          color: Color(0xFFA3E635),
                          letterSpacing: 0.3,
                        ),
                      ),
                      SizedBox(width: 8),
                      Icon(
                        Icons.check_circle_outline_rounded,
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
