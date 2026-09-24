/*
 * File name: slide_item_widget.dart
 * Last modified: 2024.09.24
 * Author: ClubeMkt - https://clubemkt.online
 * Copyright (c) 2024 CitasYa Bolivia
 */

import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../../../models/slide_model.dart';
import '../../../routes/app_routes.dart';

class SlideItemWidget extends StatelessWidget {
  final Slide slide;

  const SlideItemWidget({
    required this.slide,
    Key? key,
  }) : super(key: key);

  void _onSlideTap() {
    if (slide.salon.hasData) {
      Get.toNamed(Routes.SALON, arguments: {'salon': slide.salon, 'heroTag': 'salon_slide_item'});
    } else if (slide.eService.hasData) {
      Get.toNamed(Routes.E_SERVICE, arguments: {'eService': slide.eService, 'heroTag': 'slide_item'});
    }
  }

  @override
  Widget build(BuildContext context) {
    final double screenWidth = MediaQuery.of(context).size.width;
    final bool isDesktop = screenWidth > 850;

    return GestureDetector(
      onTap: _onSlideTap,
      child: Center(
        child: Container(
          margin: EdgeInsets.symmetric(horizontal: isDesktop ? 12.0 : 0.0),
          constraints: const BoxConstraints(maxWidth: 1100),
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(isDesktop ? 18.0 : 0.0),
            boxShadow: isDesktop
                ? [
                    BoxShadow(
                      color: Colors.black.withOpacity(0.08),
                      blurRadius: 16,
                      offset: const Offset(0, 4),
                    ),
                  ]
                : null,
          ),
          child: ClipRRect(
            borderRadius: BorderRadius.circular(isDesktop ? 18.0 : 0.0),
            child: AspectRatio(
              aspectRatio: 16 / 9,
              child: Stack(
                fit: StackFit.expand,
                children: [
                  CachedNetworkImage(
                    fit: BoxFit.contain,
                    imageUrl: slide.image.url,
                    placeholder: (context, url) => Container(
                      color: const Color(0xFF012B18),
                      child: const Center(
                        child: SizedBox(
                          width: 28,
                          height: 28,
                          child: CircularProgressIndicator(
                            strokeWidth: 2.5,
                            valueColor: AlwaysStoppedAnimation<Color>(Color(0xFF84CC16)),
                          ),
                        ),
                      ),
                    ),
                    errorWidget: (context, url, error) => Container(
                      color: const Color(0xFF0F172A),
                      child: const Center(
                        child: Icon(
                          Icons.image_not_supported_outlined,
                          color: Color(0xFF94A3B8),
                          size: 32,
                        ),
                      ),
                    ),
                  ),
                  if (slide.text.isNotEmpty || slide.button.isNotEmpty)
                    Container(
                      padding: const EdgeInsets.symmetric(vertical: 16, horizontal: 20),
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          if (slide.text.isNotEmpty)
                            Text(
                              slide.text,
                              style: Get.textTheme.titleLarge?.merge(
                                TextStyle(
                                  color: slide.textColor,
                                  fontWeight: FontWeight.w800,
                                ),
                              ),
                              overflow: TextOverflow.fade,
                              maxLines: 2,
                            ),
                          if (slide.button.isNotEmpty) ...[
                            const SizedBox(height: 10),
                            MaterialButton(
                              onPressed: _onSlideTap,
                              padding: const EdgeInsets.symmetric(vertical: 8, horizontal: 20),
                              color: slide.buttonColor,
                              shape: const StadiumBorder(),
                              elevation: 0,
                              child: Text(
                                slide.button,
                                style: const TextStyle(
                                  color: Colors.white,
                                  fontWeight: FontWeight.w700,
                                  fontSize: 13,
                                ),
                              ),
                            ),
                          ],
                        ],
                      ),
                    ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}
