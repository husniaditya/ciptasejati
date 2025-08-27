PR.registerLangHandler(
  PR.createSimpleLexer(
    // Simple tokens (whitespace, etc.)
    [
      ["pln", /^[\t\n\f\r ]+/, null, " \t\r\n\f"]
    ],
    // More complex tokens
    [
      ["str", /^"(?:[^\n\f\r"\\]|\\(?:\r\n?|\n|\f)|\\[\s\S])*"/, null],
      ["str", /^'(?:[^\n\f\r'\\]|\\(?:\r\n?|\n|\f)|\\[\s\S])*'/, null],
      ["lang-css-str", /^url\(([^"')]+)\)/i],
      ["kwd", /^(?:url|rgb|!important|@import|@page|@media|@charset|inherit)(?=[^\w-]|$)/i, null],
      ["lang-css-kw", /^(-?(?:[_a-z]|\\[\da-f]+ ?)(?:[\w-]|\\[\da-f]+ ?)*)\s*:/i],
      ["com", /^\/\*[^*]*\*+(?:[^*/][^*]*\*+)*\//],
      ["com", /^(?:<!--|-->)/],
      ["lit", /^(?:\d+|\d*\.\d+)(?:%|[a-z]+)?/i],
      ["lit", /^#[\da-f]{3,6}\b/i],
      ["pln", /^-?(?:[_a-z]|\\[\da-f]+ ?)(?:[\w-]|\\[\da-f]+ ?)*/i],
      ["pun", /^[^\s\w"']+/]
    ]
  ),
  ["css"]
);

PR.registerLangHandler(
  PR.createSimpleLexer(
    [],
    [["kwd", /^-?(?:[_a-z]|\\[\da-f]+ ?)(?:[\w-]|\\[\da-f]+ ?)*/i]]
  ),
  ["css-kw"]
);

PR.registerLangHandler(
  PR.createSimpleLexer(
    [],
    [["str", /^[^"')]+/]]
  ),
  ["css-str"]
);