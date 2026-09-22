class CustomTrace {
  final StackTrace _trace;

  String? fileName;
  String? functionName;
  String? callerFunctionName;
  String? message;
  int? lineNumber;
  int? columnNumber;

  CustomTrace(this._trace, {this.message}) {
    try {
      _parseTrace();
    } catch (_) {}
  }

  String _getFunctionNameFromFrame(String frame) {
    try {
      var currentTrace = frame;
      var indexOfWhiteSpace = currentTrace.indexOf(' ');
      if (indexOfWhiteSpace == -1) return currentTrace;

      var subStr = currentTrace.substring(indexOfWhiteSpace).trim();
      var indexOfFunction = subStr.indexOf(RegExp(r'[A-Za-z0-9]'));
      if (indexOfFunction == -1) return subStr;

      subStr = subStr.substring(indexOfFunction);
      var nextSpace = subStr.indexOf(' ');
      if (nextSpace != -1) {
        subStr = subStr.substring(0, nextSpace);
      }
      return subStr;
    } catch (_) {
      return '';
    }
  }

  void _parseTrace() {
    try {
      var frames = this._trace.toString().split("\n");
      if (frames.isNotEmpty && frames[0].isNotEmpty) {
        this.functionName = _getFunctionNameFromFrame(frames[0]);
      }
      if (frames.length > 1 && frames[1].isNotEmpty) {
        this.callerFunctionName = _getFunctionNameFromFrame(frames[1]);
      }
      if (frames.isNotEmpty && frames[0].isNotEmpty) {
        var traceString = frames[0];
        var indexOfFileName = traceString.indexOf(RegExp(r'[A-Za-z]+.dart'));
        if (indexOfFileName != -1) {
          var fileInfo = traceString.substring(indexOfFileName);
          var listOfInfos = fileInfo.split(":");
          if (listOfInfos.isNotEmpty) {
            this.fileName = listOfInfos[0];
          }
          if (listOfInfos.length > 1) {
            this.lineNumber = int.tryParse(listOfInfos[1]);
          }
          if (listOfInfos.length > 2) {
            var columnStr = listOfInfos[2].replaceFirst(")", "");
            this.columnNumber = int.tryParse(columnStr);
          }
        }
      }
    } catch (_) {}
  }

  @override
  String toString() {
    if (message != null && functionName != null && functionName!.isNotEmpty) {
      return "$message | ($functionName)";
    }
    return message ?? functionName ?? '';
  }
}
